<?php

use yii\db\Migration;

/**
 * Handles the creation of table `log`.
 */
class m180910_162938_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'model_class' => $this->string()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'action' => $this->string(10)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'values' => $this->text()->notNull(),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%log}}', ['user_id']);
        $this->createIndex('action', '{{%log}}', ['action']);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('log');
    }
}
