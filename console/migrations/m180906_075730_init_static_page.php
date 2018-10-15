<?php

use yii\db\Migration;

/**
 * Class m180906_075730_init_static_page
 */
class m180906_075730_init_static_page extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%static_page}}', [
            'id'      => $this->primaryKey(),
            'title'   => $this->string()->notNull(),
            'content' => $this->text(),
            'status'  => $this->smallInteger(1)->defaultValue(1),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('static_page');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180906_075730_init_static_page cannot be reverted.\n";

        return false;
    }
    */
}
