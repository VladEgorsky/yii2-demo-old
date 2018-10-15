<?php

use yii\db\Migration;

/**
 * Class m180907_085220_init_comment_table
 */
class m180907_085220_init_comment_table extends Migration
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
        $this->createTable('comment', [
            'id'           => $this->primaryKey(),
            'news_id'      => $this->integer()->notNull(),
            'user_name'    => $this->string()->notNull(),
            'user_address' => $this->string()->notNull(),
            'comment'      => $this->text()->notNull(),
            'rate'         => $this->integer()->defaultValue(0),
            'status'       => $this->smallInteger(1)->defaultValue(0),
            'created_at'   => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->addForeignKey('fk_comment_to_news', 'comment', 'news_id', 'news', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_comment_to_news', 'comment');
        $this->dropTable('comment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180907_085220_init_comment_table cannot be reverted.\n";

        return false;
    }
    */
}
