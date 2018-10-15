<?php

use backend\models\User;
use yii2mod\rbac\migrations\Migration;

class m180830_212321_fill_rbac_tables extends Migration
{
    public function safeUp()
    {
        $authManager = \Yii::$app->authManager;
        $authManager->removeAll();

        // Roles
        $guest = $authManager->createRole('guest');
        $user = $authManager->createRole('user');
        $manager = $authManager->createRole('manager');
        $admin = $authManager->createRole('admin');

        $authManager->add($guest);
        $authManager->add($user);
        $authManager->add($manager);
        $authManager->add($admin);

        // Permissions
        $viewPages = $authManager->createPermission('viewPages');
        $submitFeedbackForm = $authManager->createPermission('submitFeedbackForm');
        $makeComments = $authManager->createPermission('makeComments');
        $moderateComments = $authManager->createPermission('moderateComments');
        $editPageTemplate = $authManager->createPermission('editPageTemplate');
        $editPages = $authManager->createPermission('editPages');
        $createPages = $authManager->createPermission('createPages');
        $deletePages = $authManager->createPermission('deletePages');
        $changeConfig = $authManager->createPermission('changeConfig');
        $manageUsersAndRoles = $authManager->createPermission('manageUsersAndRoles');
        $viewAdminPanel = $authManager->createPermission('viewAdminPanel');

        $authManager->add($viewPages);
        $authManager->add($submitFeedbackForm);
        $authManager->add($makeComments);
        $authManager->add($moderateComments);
        $authManager->add($editPageTemplate);
        $authManager->add($editPages);
        $authManager->add($createPages);
        $authManager->add($deletePages);
        $authManager->add($changeConfig);
        $authManager->add($manageUsersAndRoles);
        $authManager->add($viewAdminPanel);

        // Assign permissions to roles
        $authManager->addChild($guest, $viewPages);
        $authManager->addChild($guest, $submitFeedbackForm);
        $authManager->addChild($guest, $makeComments);

        $authManager->addChild($user, $guest);

        $authManager->addChild($manager, $moderateComments);
        $authManager->addChild($manager, $editPageTemplate);
        $authManager->addChild($manager, $editPages);
        $authManager->addChild($manager, $createPages);
        $authManager->addChild($manager, $viewAdminPanel);
        $authManager->addChild($manager, $user);

        $authManager->addChild($admin, $deletePages);
        $authManager->addChild($admin, $changeConfig);
        $authManager->addChild($admin, $manageUsersAndRoles);
        $authManager->addChild($admin, $manager);

        // Assign roles to users
        $authManager->assign($admin, 1);
        $authManager->assign($admin, 2);
        $authManager->assign($admin, 3);
        $authManager->assign($manager, 4);
        $authManager->assign($user, 5);

        $cnt = User::find()->count();
        for ($n = 6; $n <= $cnt; $n++) {
            $authManager->assign($guest, $n);
        }
    }

    public function safeDown()
    {
        $authManager = \Yii::$app->authManager;
        $authManager->removeAll();
    }
}