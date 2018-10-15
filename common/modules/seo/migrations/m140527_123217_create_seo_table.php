<?php

/**
 * Class m140527_123217_create_seo_table
 */
class m140527_123217_create_seo_table extends \yii\db\Migration
{
    /**
     * @return bool|void
     */
    public function up()
    {
        $this->createTable('seo', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'h1' => $this->string(255),
            'description' => $this->string(255),
            'keywords' => $this->string(255),
            'head_block' => $this->text(),
            'text_data' => $this->text(),
            'external_link' => $this->string(255)->notNull(),
            'internal_link' => $this->string(255)->notNull(),
            'noindex' => $this->smallInteger(1)->defaultValue(0),
            'nofollow' => $this->smallInteger(1)->defaultValue(0),
            'in_sitemap' => $this->smallInteger(1)->defaultValue(1),
            'is_canonical' => $this->smallInteger(1)->defaultValue(0),
            'model_name' => $this->string(255)->defaultValue(null),
            'model_id' => $this->integer()->defaultValue(null),
            'status' => $this->smallInteger(1)->defaultValue(1),
            'updated_at' => $this->timestamp()->notNull()->defaultValue('1971-01-01 00:00:00'),
            'created_at' => $this->timestamp()->notNull()->defaultValue('1971-01-01 00:00:00'),
        ]);

        $this->createIndex('idx_seo_indexes', 'seo', ['external_link', 'model_name', 'model_id']);
    }

    /**
     * @return bool|void
     */
    public function down()
    {
        $this->dropTable('seo');
    }
}
