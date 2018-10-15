<?php

namespace backend\components\rbac\controllers;

use yii\rbac\Item;
use backend\components\rbac\controllers\base\ItemController;

/**
 * Class RoleController
 *
 * @package yii2mod\rbac\controllers
 */
class RoleController extends ItemController
{
    /**
     * @var int
     */
    protected $type = Item::TYPE_ROLE;

    /**
     * @var array
     */
    protected $labels = [
        'Item' => 'Role',
        'Items' => 'Roles',
    ];
}
