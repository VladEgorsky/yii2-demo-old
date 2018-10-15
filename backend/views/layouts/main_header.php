<?php
/**
 * @var $this \yii\web\View
 * @var $directoryAsset string
 */

use backend\models\Advertise;
use backend\models\Comment;
use backend\models\Story;
use backend\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

$inactiveUsersAmount = User::find()->where(['status' => User::STATUS_INACTIVE])->count();
$inactiveCommentsAmount = Comment::find()->where(['status' => Comment::STATUS_HIDDEN])->count();
$inactiveStoriesAmount = Story::find()->where(['status' => Story::STATUS_NEW])->count();
$inactiveAdvertisesAmount = Advertise::find()->where(['status' => Advertise::STATUS_NEW])->count();

?>

<header class="main-header">
    <?= Html::a('<span class="logo-mini">TST</span><span class="logo">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <?php if ($inactiveUsersAmount > 0) : ?>
                    <li class="dropdown notifications-menu">
                        <a href="<?= Url::to(['/user/index', 'UserSearch[status]' => User::STATUS_INACTIVE]) ?>"
                           title="<?= $inactiveUsersAmount ?> Users needs to be activated">
                            <i class="fa fa-user-plus"></i>
                            <span class="label label-danger"><?= $inactiveUsersAmount ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($inactiveCommentsAmount > 0) : ?>
                    <li class="dropdown notifications-menu">
                        <a href="<?= Url::to(['/comment/index', 'CommentSearch[status]' => Comment::STATUS_HIDDEN]) ?>"
                           title="<?= $inactiveCommentsAmount ?> Comments needs to be moderated">
                            <i class="fa fa-flag-o"></i>
                            <span class="label label-warning"><?= $inactiveCommentsAmount ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($inactiveStoriesAmount > 0) : ?>
                    <li class="dropdown notifications-menu">
                        <a href="<?= Url::to(['/story/index', 'StorySearch[status]' => Story::STATUS_NEW]) ?>"
                           title="<?= $inactiveStoriesAmount ?> Stories needs to be moderated">
                            <i class="fa fa-envelope-o"></i>
                            <span class="label label-success"><?= $inactiveStoriesAmount ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($inactiveAdvertisesAmount > 0) : ?>
                    <li class="dropdown notifications-menu">
                        <a href="<?= Url::to(['/advertise/index', 'AdvertiseSearch[status]' => Advertise::STATUS_NEW]) ?>"
                           title="<?= $inactiveAdvertisesAmount ?> new Advertises">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning"><?= $inactiveAdvertisesAmount ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu" style="width:180px;">
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div>
                                <?= Html::a('<i class="fa fa-key" aria-hidden="true"></i>&nbsp; Change Password',
                                    Url::to(['/site/change-password']), [
                                        'class' => 'btn btn-default btn-flat', 'style' => 'width:100%; text-align:left;',
                                    ]) ?>

                                <?= Html::a('<i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp; Log out',
                                    Url::to(['/site/logout']), [
                                        'class' => 'btn btn-default btn-flat', 'data-method' => 'POST',
                                        'style' => 'width:100%; margin-top:8px; text-align:left;',
                                    ]) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <li style="min-width: 30px;"></li>
            </ul>
        </div>
    </nav>
</header>
