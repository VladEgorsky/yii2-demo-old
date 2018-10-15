<?php

use yii\db\Migration;

/**
 * Class m180910_093904_init_adw_and_story_tables
 */
class m180910_093904_init_adw_and_story_tables extends Migration
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

        $this->createTable('story', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull(),
            'email'      => $this->string()->notNull(),
            'content'    => $this->text()->notNull(),
            'files'      => $this->text(),
            'status'     => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->createTable('advertise', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string()->notNull(),
            'email'      => $this->string()->notNull(),
            'content'    => $this->text()->notNull(),
            'files'      => $this->text(),
            'status'     => $this->smallInteger()->defaultValue(0),
            'created_at' => $this->integer()->unsigned(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('story');
        $this->dropTable('advertise');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180910_093904_init_adw_and_story_tables cannot be reverted.\n";

        return false;
    }
    */
}
