<?php

use yii\db\Migration;

/**
 * Class m180831_053602_add_rel_table_tag_section
 */
class m180831_053602_add_rel_table_tag_section extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tag}}', 'ordering', $this->integer()->defaultValue(0)->after('title'));

        $this->createTable('{{%section_tag}}', [
            'tag_id' => $this->integer()->notNull(),
            'section_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_section_tag', 'section_tag', ['section_id', 'tag_id'], true);

        $this->addForeignKey('fk_section_tag_to_section', '{{%section_tag}}', 'section_id', '{{%section}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_section_tag_to_news', '{{%section_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tag}}', 'ordering');

        $this->dropForeignKey('fk_section_tag_to_section', '{{%section_tag}}');
        $this->dropForeignKey('fk_section_tag_to_news', '{{%section_tag}}');

        $this->dropTable('{{%section_tag}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180831_053602_add_rel_table_tag_section cannot be reverted.\n";

        return false;
    }
    */
}
