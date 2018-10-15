<?php
/**
 * @var $this \yii\web\View
 * @var $content string
 */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii2mod\alert\Alert;

backend\assets\AppAsset::register($this);
dmstr\web\AdminLteAsset::register($this);
phpnt\pace\PaceAsset::register($this);
yii2mod\alert\AlertAsset::register($this);

$this->registerJs('var AdminLTEOptions = {enableControlSidebar: false}', $this::POS_BEGIN);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <!--meta http-equiv="X-UA-Compatible" content="IE=edge"-->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition sidebar-mini <?= \dmstr\helpers\AdminLteHelper::skinClass() ?>">
<?php $this->beginBody() ?>

<div class="wrapper">
    <?php $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist'); ?>
    <?= $this->render('main_header.php', ['directoryAsset' => $directoryAsset]) ?>
    <?= $this->render('main_left.php', ['directoryAsset' => $directoryAsset]) ?>

    <div class="content-wrapper">
        <section class="content-header">
            <?php
            if (isset($this->blocks['content-header'])) {
                echo Html::tag('h1', $this->blocks['content-header']);
            }

            echo Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]);
            ?>
        </section>

        <section class="content">
            <?= Alert::widget() ?>
            <?= $content ?>
        </section>
    </div>

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 1.0
        </div>

        <div class="copyright">
            <strong> Copyright &copy; 2018 </strong>
            All rights reserved.
        </div>
    </footer>

    <?php //echo $this->render('main_right', ['directoryAsset' => $directoryAsset]) ?>

    <div class="pace  pace-inactive">
        <div class="pace-progress" data-progress-text="100%" data-progress="99">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
