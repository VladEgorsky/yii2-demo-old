<?php
/**
 * @var $this \yii\web\View
 * @var $directoryAsset string
 */

?>

<aside class="main-sidebar">
    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel" style="min-height: 70px;">
            <!--div class="pull-left image">
                <img src="" class="img-circle" alt="User Image"/>
            </div-->
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->getFullName() ?></p>

                <i class="fa fa-circle text-success"></i>
                <?= Yii::t('app', 'online') ?>
            </div>
        </div>

        <!-- search form -->
        <!--        <form action="#" method="get" class="sidebar-form">-->
        <!--            <div class="input-group">-->
        <!--                <input type="text" name="q" class="form-control" placeholder="Search..."/>-->
        <!--                <span class="input-group-btn">-->
        <!--                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>-->
        <!--                </button>-->
        <!--              </span>-->
        <!--            </div>-->
        <!--        </form>-->
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'activateItems' => true,
                'activateParents' => true,
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    [
                        'label' => Yii::t('app', 'MAIN MENU'),
                        'options' => ['class' => 'header']
                    ],
                    [
                        'label' => Yii::t('app', 'News'),
                        'icon' => 'newspaper-o',
                        'url' => ['/news/index']
                    ],
                    [
                        'label' => Yii::t('app', 'Sections'),
                        'icon' => 'bookmark',
                        'url' => ['/section/index']
                    ],
                    [
                        'label' => Yii::t('app', 'Tags'),
                        'icon' => 'hashtag',
                        'url' => ['/tag/index']
                    ],
                    [
                        'label' => Yii::t('app', 'Static pages'),
                        'icon'  => 'file',
                        'url'   => ['/static-page/index']
                    ],
                    [
                        'label' => Yii::t('app', 'Comments'),
                        'icon'  => 'comments-o',
                        'url'   => ['/comment/index']
                    ],
                    [
                        'label' => Yii::t('app', 'Subscribe'),
                        'icon'  => 'rss-square',
                        'url'   => ['/subscribe/index']
                    ],
                    [
                        'label' => Yii::t('app', 'User stories'),
                        'icon'  => 'file',
                        'url'   => ['/story/index']
                    ],
                    [
                        'label' => Yii::t('app', 'User advertise'),
                        'icon'  => 'file',
                        'url'   => ['/advertise/index']
                    ],
                    [
                        'label' => Yii::t('app', 'Templates'),
                        'icon' => 'edit',
                        'url' => ['/template/index'],
                    ],
                    [
                        'label' => Yii::t('app', 'Logs'),
                        'icon' => 'book',
                        'url' => ['/log/index']
                    ],
                    [
                        'label' => Yii::t('app', 'Users'),
                        'icon' => 'users',
                        'items' => [
                            ['label' => 'User List', 'icon' => 'user-plus',
                                'url' => ['/user/index']],
                            ['label' => 'Routes', 'icon' => 'location-arrow',
                                'url' => ['/rbac/route']],
                            ['label' => 'Roles', 'icon' => 'user-circle-o',
                                'url' => ['/rbac/role']],
                            ['label' => 'Permissions', 'icon' => 'user-times',
                                'url' => ['/rbac/permission']],
                            ['label' => 'Rules', 'icon' => 'scissors',
                                'url' => ['/rbac/rule']],
                        ],
                    ],
//                    [
//                        'label' => Yii::t('app', 'Settings'),
//                        'icon' => 'cogs',
//                        'items' => [
//
//                        ],
//                    ],
                ],
            ]
        ) ?>
    </section>
</aside>
