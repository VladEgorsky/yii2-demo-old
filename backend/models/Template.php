<?php
namespace backend\models;

use backend\components\LoggingBehavior;
use backend\components\Y;
use common\models\Advert;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class Template extends \common\models\Template
{
    const SCENARIO_ADVERT = 'advert';
    const SCENARIO_MAINSECTION = 'mainsection';

    public $sectionIds = [];
    public $mainSectionIds = [];
    public $tagIds = [];
    public $advertKeys = [];
    public $oldTemplateLocationAttributes;

    public function getIsMainPageUpperBlock()
    {
        return $this->scenario == Template::SCENARIO_MAINSECTION && $this->mainSectionIds == [0];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'log' => [
                    'class' => LoggingBehavior::class,
                    'relatedModels' => ['Section', 'Tag'],
                    'excludedAttribs' => ['id', 'data'],
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // SCENARIO_DEFAULT is for Sections and Tags (advertKeys = [])
        $scenarios[self::SCENARIO_DEFAULT] = ['status', 'items_amount',
            'items_classes', 'data', 'sectionIds', 'tagIds'];
        $scenarios[self::SCENARIO_ADVERT] = ['status', 'items_amount',
            'items_classes', 'data', 'sectionIds', 'tagIds', 'advertKeys'];
        $scenarios[self::SCENARIO_MAINSECTION] = ['status', 'items_amount',
            'items_classes', 'data', 'mainSectionIds'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['sectionIds', 'tagIds'], 'each', 'rule' => ['integer', 'min' => 1]],
                ['sectionIds', 'validateSectionIds', 'skipOnEmpty' => false, 'except' => self::SCENARIO_MAINSECTION],
                [['advertKeys'], 'required', 'on' => self::SCENARIO_ADVERT],
                [['mainSectionIds'], 'required', 'on' => self::SCENARIO_MAINSECTION,
                    'message' => 'Please select mainpage block'],
                //[['advertKeys'], 'in', 'range' => array_keys(static::getAdvertListData())],
            ]
        );
    }

    /**
     *
     */
    public function validateSectionIds($attribute, $params)
    {
        if (empty($this->sectionIds) && empty($this->tagIds)) {
            $this->addError('sectionIds', 'Please select Section(s) or Tag(s)');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'sectionIds' => 'Sections',
                'tagIds' => 'Tags',
                'advertKeys' => 'Advert Locations',
            ]
        );
    }


    ////////////////////////////////////////////////////////////////////////////////
    ///                     RELATIONS
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Section::class, ['id' => 'location_id'])
            ->viaTable('template_location', ['template_id' => 'id'], function ($query) {
                $query->onCondition(['location_key' => static::LOCATION_SECTION]);
            });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMainSections()
    {
        return $this->hasMany(Section::class, ['id' => 'location_id'])
            ->viaTable('template_location', ['template_id' => 'id'], function ($query) {
                $query->onCondition(['location_key' => static::LOCATION_MAINSECTION]);
            });
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::class, ['id' => 'location_id'])
            ->viaTable('template_location', ['template_id' => 'id'], function ($query) {
                $query->onCondition(['location_key' => static::LOCATION_TAG]);
            });
    }

    /**
     * @return array
     */
    public function getAdverts()
    {
//        $advertIds = (new Query())->select('advert_key')->from('{{%template_location}}')
//            ->where(['and', ['template_id' => $this->id], ['not', ['advert_key' => null]]])->column();
//
//        return empty($advertIds) ? [] : Advert::getListDataByIds($advertIds);
        return [];
    }

    ////////////////////////////////////////////////////////////////////////////////
    ///                     EVENTS
    public function afterDelete()
    {
        Yii::$app->db->createCommand()->delete('{{%template_location}}', [
            'template_id' => $this->id])->execute();

        parent::afterDelete();
    }

    ////////////////////////////////////////////////////////////////////////////////
    ///                 updateModelWithJunctionTable
    ////////////////////////////////////////////////////////////////////////////////
    /**
     * ATTENTION: Model must be validated before executing this method.
     */
    public function updateModelWithJunctionTable()
    {
        $tab = '{{%template_location}}';
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->save(false);

            if ($this->scenario == static::SCENARIO_MAINSECTION) {
                $this->compareSectionAndTagAttributes('mainSectionIds', static::LOCATION_MAINSECTION);
            } else {
                $this->compareSectionAndTagAttributes('sectionIds', static::LOCATION_SECTION);
                $this->compareSectionAndTagAttributes('tagIds', static::LOCATION_TAG);
            }

            if ($this->scenario == static::SCENARIO_ADVERT) {
                $this->compareAdvertAttribute();
            }

            if (count($this->deleteCondition) > 1) {
                Yii::$app->db->createCommand()->delete($tab, $this->deleteCondition)->execute();
            }
            if (!empty($this->arrayToBatchInsert)) {
                Yii::$app->db->createCommand()->batchInsert($tab, [
                    'location_id', 'location_key', 'template_id', 'advert_key'
                ], $this->arrayToBatchInsert)->execute();
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('items_amount', $e->getMessage());
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $this->addError('items_amount', $e->getMessage());
        }

        return false;
    }

    protected $deleteCondition = ['or'];
    protected $arrayToBatchInsert = [];

    /**
     * @param $attrName string          mainSectionIds || sectionIds || tagIds
     * @param $locationKey string       LOCATION_MAINSECTION || LOCATION_SECTION || LOCATION_TAG
     * @return bool
     */
    protected function compareSectionAndTagAttributes($attrName, $locationKey)
    {
        $oldIds = $this->oldTemplateLocationAttributes[$attrName] ?? [];
        $newIds = empty($this->$attrName) ? [] : $this->$attrName;
        $idsToDelete = array_diff($oldIds, $newIds);
        $idsToInsert = array_diff($newIds, $oldIds);

        if (!empty($idsToDelete)) {
            $this->deleteCondition[] = [
                'template_id' => $this->id,
                'location_id' => $idsToDelete,
                'location_key' => $locationKey
            ];
        }

        // If scenario=DEFAULT add single record to template_location
        // For every new sectionId & tagId
        // If scenario=ADVERT add new records to template_location
        // for every existing advertKey
        if (!empty($idsToInsert)) {
            if ($this->scenario == static::SCENARIO_ADVERT) {

                foreach ($this->advertKeys as $advertKey) {
                    $this->arrayToBatchInsert = array_merge(
                        $this->arrayToBatchInsert,
                        Y::getRowsForBatchInsert($idsToInsert, [$locationKey, $this->id, $advertKey])
                    );
                }

            } else {
                $this->arrayToBatchInsert = array_merge(
                    $this->arrayToBatchInsert,
                    Y::getRowsForBatchInsert($idsToInsert, [$locationKey, $this->id, null])
                );
            }
        }

        return true;
    }

    /**
     * !!! For scenario=ADVERT only !!!
     * @return bool
     */
    protected function compareAdvertAttribute()
    {
        $oldKeys = $this->oldTemplateLocationAttributes['advertKeys'] ?? [];
        $newKeys = $this->advertKeys;
        $keysToDelete = array_diff($oldKeys, $newKeys);
        $keysToInsert = array_diff($newKeys, $oldKeys);

        if (!empty($keysToDelete)) {
            $this->deleteCondition[] = ['template_id' => $this->id, 'advert_key' => $keysToDelete];
        }

        if (!empty($keysToInsert)) {
            foreach ($keysToInsert as $key) {

                // We must add only old and not deleted sectionIds & tagIds here
                // New values already added above.
                $sectionIds = array_intersect($this->oldTemplateLocationAttributes['sectionIds'], $this->sectionIds);
                $tagIds = array_intersect($this->oldTemplateLocationAttributes['tagIds'], $this->tagIds);

                $this->arrayToBatchInsert = array_merge(
                    $this->arrayToBatchInsert,
                    Y::getRowsForBatchInsert($sectionIds, [static::LOCATION_SECTION, $this->id, $key])
                );
                $this->arrayToBatchInsert = array_merge(
                    $this->arrayToBatchInsert,
                    Y::getRowsForBatchInsert($tagIds, [static::LOCATION_TAG, $this->id, $key])
                );
            }
        }

        return true;
    }


    ////////////////////////////////////////////////////////////////////////////////
    ///                     HELPERS
    /**
     * @return bool
     */
    public function fillLocationIds()
    {
        $locations = (new Query())->from('{{%template_location}}')
            ->where(['template_id' => $this->id])->all();

        foreach ($locations as $loc) {
            if ($loc['location_key'] == static::LOCATION_SECTION) {
                if (!in_array($loc['location_id'], $this->sectionIds)) {
                    $this->sectionIds[] = $loc['location_id'];
                }
            }
            if ($loc['location_key'] == static::LOCATION_MAINSECTION) {
                if (!in_array($loc['location_id'], $this->mainSectionIds)) {
                    $this->mainSectionIds[] = $loc['location_id'];
                }
            }
            if ($loc['location_key'] == static::LOCATION_TAG) {
                if (!in_array($loc['location_id'], $this->tagIds)) {
                    $this->tagIds[] = $loc['location_id'];
                }
            }
            if (!is_null($loc['advert_key'])) {
                if (!in_array($loc['advert_key'], $this->advertKeys)) {
                    $this->advertKeys[] = $loc['advert_key'];
                }
            }
        }

        $this->oldTemplateLocationAttributes = [
            'sectionIds' => $this->sectionIds,
            'mainSectionIds' => $this->mainSectionIds,
            'tagIds' => $this->tagIds,
            'advertKeys' => $this->advertKeys,
        ];

        return true;
    }

    /**
     * Output :
     * SECTIONS: Section1, Section2, Section3 ...
     * TAGS: Tag1, Tag2, Tag3 ...
     * ADVERTS: Advert1, Advert2 ...
     *
     * @return string
     */
    public function getLocationNames()
    {
        $val = '';
        $mainSections = array_map(function ($m) {
            return $m->title;
        }, $this->mainSections);
        if (!empty($mainSections)) {
            $val .= 'MAINPAGE: SECTIONS - ' . implode(', ', $mainSections) . '<br />';
        }

        $adverts = array_values($this->adverts);
        if (!empty($adverts)) {
            $val .= 'ADVERTS: ' . implode(', ', $adverts) . '<br />';
        }

        $sections = array_map(function ($m) {
            return $m->title;
        }, $this->sections);
        if (!empty($sections)) {
            $val .= 'SECTIONS: ' . implode(', ', $sections) . '<br />';
        }

        $tags = array_map(function ($m) {
            return $m->title;
        }, $this->tags);
        if (!empty($tags)) {
            $val .= 'TAGS: ' . implode(', ', $tags) . '<br />';
        }

        if (empty($val) && (new Query())->from('{{%template_location}}')
                ->where(['location_key' => static::LOCATION_MAINSECTION, 'location_id' => static::LOCATION_MAIN_UPPERBLOCK_ID])
                ->exists()) {
            $val .= 'MAINPAGE: UPPER BLOCK ' . '<br />';
        }

        return empty($val) ? $val : substr($val, 0, -6);
    }

    public function setScenarioByLocationIds()
    {
        if (!empty($this->mainSectionIds)) {
            $scenario = static::SCENARIO_MAINSECTION;
        } elseif (!empty($this->advertKeys)) {
            $scenario = static::SCENARIO_ADVERT;
        } else {
            $scenario = static::SCENARIO_DEFAULT;
        }

        $this->scenario = $scenario;
        return true;
    }

}