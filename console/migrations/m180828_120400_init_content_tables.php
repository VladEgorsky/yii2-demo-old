<?php

use yii\db\Migration;

/**
 * Class m180828_120400_init_content_tables
 */
class m180828_120400_init_content_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%section}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'ordering' => $this->integer()->defaultValue(0),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->timestamp()->notNull()->defaultValue('1971-01-01 00:00:00'),
            'updated_at' => $this->timestamp()->notNull()->defaultValue('1971-01-01 00:00:00'),
        ], $tableOptions);

        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'short_content' => $this->text(),
            'content' => $this->text(),
            'cover_image' => $this->string()->null(),
            'cover_video' => $this->string()->null(),
            'author' => $this->string(),
            'comment_count' => $this->integer()->defaultValue(0),
            'ordering' => $this->integer()->defaultValue(0),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'created_at' => $this->timestamp()->notNull()->defaultValue('1971-01-01 00:00:00'),
            'updated_at' => $this->timestamp()->notNull()->defaultValue('1971-01-01 00:00:00'),
        ], $tableOptions);

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%news_section}}', [
            'section_id' => $this->integer()->notNull(),
            'news_id' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%news_tag}}', [
            'tag_id' => $this->integer()->notNull(),
            'news_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_news_section', 'news_section', ['section_id', 'news_id'], true);
        $this->createIndex('idx_news_tag', 'news_tag', ['tag_id', 'news_id'], true);

        $this->addForeignKey('fk_news_section_to_section', '{{%news_section}}', 'section_id', '{{%section}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_news_section_to_news', '{{%news_section}}', 'news_id', '{{%news}}', 'id', 'CASCADE');

        $this->addForeignKey('fk_news_tag_to_section', '{{%news_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_news_tag_to_news', '{{%news_tag}}', 'news_id', '{{%news}}', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_news_section_to_section', '{{%news_section}}');
        $this->dropForeignKey('fk_news_section_to_news', '{{%news_section}}');
        $this->dropForeignKey('fk_news_tag_to_section', '{{%news_tag}}');
        $this->dropForeignKey('fk_news_tag_to_news', '{{%news_tag}}');

        $this->dropTable('{{%news_tag}}');
        $this->dropTable('{{%news_section}}');
        $this->dropTable('{{%tag}}');
        $this->dropTable('{{%news}}');
        $this->dropTable('{{%section}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180828_120400_init_content_tables cannot be reverted.\n";

        return false;
    }
    */
}
