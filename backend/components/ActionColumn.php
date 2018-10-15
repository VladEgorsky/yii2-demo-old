<?php

namespace backend\components;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This class must work with ModalAjax widget
 * Added ajax-update & ajax-delete buttons,
 *      + data-action attribute for all buttons
 *
 * Class ActionColumn
 * @package backend\components
 */
class ActionColumn extends \yii\grid\ActionColumn
{
    protected function initDefaultButtons()
    {
        parent::initDefaultButtons();
        $this->initDefaultButton('ajax-update', 'pencil');
        $this->initDefaultButton('ajax-delete', 'trash');

        if (!Yii::$app->request->isAjax) {
            $this->registerAjaxDeleteJs('ajax-delete');
        }
    }

    protected function initDefaultButton($name, $iconName, $additionalOptions = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = Yii::t('yii', 'View');
                        break;
                    case 'update':
                    case 'ajax-update':
                        $title = Yii::t('yii', 'Update');
                        break;
                    case 'delete':
                    case 'ajax-delete':
                        $title = Yii::t('yii', 'Delete');
                        break;
                    default:
                        $title = ucfirst($name);
                }

                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                    'data-action' => $name,
                ], $additionalOptions, $this->buttonOptions);
                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-$iconName"]);
                return Html::a($icon, $url, $options);
            };
        }
    }

    public function createUrl($action, $model, $key, $index)
    {
        if (in_array($action, ['ajax-delete', 'ajax-update'])) {
            $params = ['id' => $key];
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;
            return Url::toRoute($params);
        }

        return parent::createUrl($action, $model, $key, $index);
    }

    protected function registerAjaxDeleteJs($actionId)
    {
        $ajaxDeleteJs = <<< JS
    $(document).on("click", "a[data-action=$actionId]", function (e) {
        e.preventDefault();
        if (!confirm("Do you want to delete this record?")) {
            return false;
        }

        var url = $(this).attr("href");
        var data = {id: $(this).closest("tr").data("key")};
        var pjaxContainerId = $(this).closest("[data-pjax-container]").attr("id");
        
        $.post(url, data, "json").done(function (response) {
            if (response === "ok") {
                if (pjaxContainerId !== undefined) {
                    $.pjax.reload({container: "#" + pjaxContainerId, timeout: 10000});
                } else {
                    location.reload();
                }
            } else if ($.isArray(response)) {
                alert(response.join("\\r\\n"));
            } else {
                alert(response);
            }

            return true;
        });
    });
JS;

        Yii::$app->view->registerJs($ajaxDeleteJs);
    }

}