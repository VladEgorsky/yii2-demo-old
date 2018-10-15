<?php

use yii\db\Migration;

/**
 * Class m180831_060729_drop_date_fields
 */
class m180831_060729_drop_date_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('section', 'created_at');
        $this->dropColumn('section', 'updated_at');
        $this->dropColumn('news', 'updated_at');
        $this->dropColumn('news', 'created_at');

        $this->addColumn('news', 'created_at', $this->integer()->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('section', 'created_at', $this->timestamp()->notNull()->defaultValue('1971-01-01 00:00:00'));
        $this->addColumn('section', 'updated_at', $this->timestamp()->notNull()->defaultValue('1971-01-01 00:00:00'));
        $this->addColumn('news', 'updated_at', $this->timestamp()->notNull()->defaultValue('1971-01-01 00:00:00'));
        $this->dropColumn('news', 'created_at');

        $this->addColumn('news', 'created_at', $this->timestamp()->defaultValue('1971-01-01 00:00:00'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180831_060729_drop_date_fields cannot be reverted.\n";

        return false;
    }
    */
}
