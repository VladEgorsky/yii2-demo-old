<?php

namespace backend\components;

use backend\models\Log;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class LoggingBehavior extends Behavior
{
    /** @var $owner \common\models\BaseModel */
    public $owner;
    public $excludedAttribs = ['id', 'updated_at'];
    public $relatedModels = [];     // ['Section', 'Tag' ... ] Model names you want to log. For ACTION_UPDATE only

    protected $oldAttribs = [];     // for ACTION_UPDATE only

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
        ];
    }

    /**
     * @return bool
     */
    public function afterDelete()
    {
        $values = $this->getAttribValues();
        return $this->log($values, Log::ACTION_DELETED);
    }

    /**
     * @return bool
     */
    public function afterInsert()
    {
        $values = $this->getAttribValues();
        return $this->log($values, Log::ACTION_CREATED);
    }

    /**
     * @return array
     */
    private function getAttribValues()
    {
        $values = [];
        foreach ($this->owner->getAttributes() as $attribName => $attribValue) {
            if (in_array($attribName, $this->excludedAttribs)) {
                continue;
            }

            $label = $this->owner->attributeLabels()[$attribName] ?? ucfirst($attribName);
            $values[] = $label . ': ' . $this->getHumanValue($attribName, $attribValue);
        }

        return $values;
    }


    /**
     *
     */
    public function beforeUpdate()
    {
        foreach ($this->owner->oldAttributes as $attribName => $oldAttribValue) {
            if (in_array($attribName, $this->excludedAttribs)) {
                continue;
            } elseif ($this->owner->getAttribute($attribName) == $oldAttribValue) {
                continue;
            }

            $this->oldAttribs[$attribName] = $oldAttribValue;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function afterUpdate()
    {
        $values = [];

        // Initial attributes
        foreach ($this->oldAttribs as $attribName => $oldAttribValue) {
            $label = $this->owner->attributeLabels()[$attribName] ?? ucfirst($attribName);
            $oldValue = $this->getHumanValue($attribName, $oldAttribValue);
            $newValue = $this->getHumanValue($attribName, $this->owner->getAttribute($attribName));

            $values[] = $label . ' changed: ' . $oldValue . ' --> ' . $newValue;
        }

        // Related attributes
        if (in_array('Section', $this->relatedModels)) {
            $oldIds = ArrayHelper::getColumn($this->owner->sections, 'id');
            $newIds = $this->owner->sectionIds;

            // ["Section SectionName1 added", "Section SectionName2 deleted"]
            if ($oldIds != $newIds) {
                $values = array_merge(
                    $values, $this->getRelatedAttribValues($oldIds, $newIds, 'section'));
            }
        }
        if (in_array('Tag', $this->relatedModels)) {
            $oldIds = ArrayHelper::getColumn($this->owner->tags, 'id');
            $newIds = $this->owner->tagIds;

            // ["Tag TagName1 added", "Tag TagName2 deleted"]
            if ($oldIds != $newIds) {
                $values =
                    array_merge($values, $this->getRelatedAttribValues($oldIds, $newIds, 'tag'));
            }
        }

        return $this->log($values, Log::ACTION_UPDATED);
    }

    private function getRelatedAttribValues($oldIds, $newIds, $tableName)
    {
        $oldIds = empty($oldIds) ? [] : $oldIds;
        $newIds = empty($newIds) ? [] : $newIds;

        $deletedIds = array_diff($oldIds, $newIds);
        $addedIds = array_diff($newIds, $oldIds);
        $items = (new Query())->select(['title', 'id'])->from($tableName)
            ->where(['id' => array_merge($deletedIds, $addedIds)])
            ->indexBy('id')->column();

        $values = [];
        foreach ($deletedIds as $deletedId) {
            $values[] = ucfirst($tableName) . ' deleted: ' . $items[$deletedId];
        }
        foreach ($addedIds as $addedId) {
            $values[] = ucfirst($tableName) . ' added: ' . $items[$addedId];
        }

        return $values;
    }

    /**
     * @param array $values
     * @param string $action
     * @return bool
     */
    private function log(array $values, string $action)
    {
        $log = new Log([
            'model_class' => get_class($this->owner),
            'model_id' => $this->owner->id,
            'user_id' => Yii::$app->user->id,
            'values' => JSON::encode($values),
            'created_at' => time(),
            'action' => $action,
            'ip' => sprintf('%u', ip2long(Yii::$app->request->userIP)),
        ]);

        return $log->save(false);
    }

    private function getHumanValue($attrName, $attrValue)
    {
        if ($attrName == 'status') {
            return $this->owner->getStatus($attrValue);
        } elseif ($attrName == 'lastvisit_at') {
            return Y::getIntegerAsDate($attrValue);
        } elseif ($attrName == 'email_verified') {
            return $this->owner->getYesNoListData()[$attrValue] ?? '';
        }

        return $attrValue ?? '';
    }
}