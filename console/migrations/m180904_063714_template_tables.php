<?php

use yii\db\Migration;

/**
 * Class m180904_063714_template_tables
 */
class m180904_063714_template_tables extends Migration
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

        $this->createTable('{{%template}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'items_amount' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'data' => $this->text()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%template_location}}', [
            'id' => $this->primaryKey(),
            'location_key' => $this->string(10)->notNull()->defaultValue(''),
            'location_id' => $this->integer()->notNull()->defaultValue(0),
            'template_id' => $this->integer()->notNull(),
            'advert_key' => $this->string(10)->null(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%template}}');
        $this->dropTable('{{%template_location}}');
    }

}
