<?php

use yii\db\Migration;

/**
 * Class m180906_063125_add_tag_status
 */
class m180906_063125_add_tag_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tag', 'status', $this->smallInteger(1)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tag', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180906_063125_add_tag_status cannot be reverted.\n";

        return false;
    }
    */
}
