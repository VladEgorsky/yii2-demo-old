<?php

use yii\db\Migration;

/**
 * Class m180918_072649_init_subscribe_table
 */
class m180918_072649_init_subscribe_table extends Migration
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

        $this->createTable('{{%subscribe}}', [
            'id'         => $this->primaryKey(),
            'name'       => $this->string(),
            'email'      => $this->string()->notNull()->unique(),
            'status'     => $this->smallInteger()->defaultValue(0),
            'period'     => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->createTable('{{%subscribe_section}}', [
            'subscribe_id' => $this->integer()->notNull(),
            'section_id'   => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%subscribe_tag}}', [
            'subscribe_id' => $this->integer()->notNull(),
            'tag_id'       => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_subscribe_section_to_subscribe', '{{%subscribe_section}}', 'subscribe_id', '{{%subscribe}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_subscribe_section_to_section', '{{%subscribe_section}}', 'section_id', '{{%section}}', 'id', 'CASCADE');

        $this->addForeignKey('fk_subscribe_tag_to_subscribe', '{{%subscribe_tag}}', 'subscribe_id', '{{%subscribe}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_subscribe_tag_to_tag', '{{%subscribe_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk_subscribe_section_to_subscribe', '{{%subscribe_section}}');
        $this->dropForeignKey('fk_subscribe_section_to_section', '{{%subscribe_section}}');
        $this->dropForeignKey('fk_subscribe_tag_to_subscribe', '{{%subscribe_tag}}');
        $this->dropForeignKey('fk_subscribe_tag_to_tag', '{{%subscribe_tag}}');

        $this->dropTable('{{%subscribe_section}}');
        $this->dropTable('{{%subscribe_tag}}');
        $this->dropTable('{{%subscribe}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180918_072649_init_subscribe_table cannot be reverted.\n";

        return false;
    }
    */
}
