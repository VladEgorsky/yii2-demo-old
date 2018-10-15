<?php

use yii\db\Migration;

/**
 * Class m180919_151917_add_rate_statistic
 */
class m180919_151917_add_rate_statistic extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('rate_statistic', [
            'id'         => $this->primaryKey(),
            'comment_id' => $this->integer()->notNull(),
            'rate'       => $this->integer()->notNull(),
            'user_data'  => $this->text(),
            'created_at' => $this->integer()->unsigned()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('rate_statistic');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180919_151917_add_rate_statistic cannot be reverted.\n";

        return false;
    }
    */
}
