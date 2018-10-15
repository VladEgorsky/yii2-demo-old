<?php

use yii\db\Migration;

/**
 * Class m180831_061357_user_table_drop_updated_column
 */
class m180831_061357_user_table_drop_updated_column extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->dropColumn('{{%user}}', 'updated_at');
    }

    public function down()
    {
        $this->addColumn('{{%user}}', 'updated_at', 'integer');
    }

}
