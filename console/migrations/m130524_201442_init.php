<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'surname' => $this->string(100)->notNull(),
            'email' => $this->string(100)->notNull()->unique(),
            'phone' => $this->string(100)->notNull()->defaultValue(''),

            'auth_key' => $this->string(32)->notNull()->unique(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(45)->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull()->defaultValue(0),
            'updated_at' => $this->integer()->notNull()->defaultValue(0),
            'lastvisit_at' => $this->integer()->notNull()->defaultValue(0),
            'email_verified' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);

        $this->createIndex('surname', '{{%user}}', ['surname', 'name']);
        $this->createIndex('status', '{{%user}}', ['status']);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
