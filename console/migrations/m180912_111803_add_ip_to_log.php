<?php

use yii\db\Migration;

/**
 * Class m180912_111803_add_ip_to_log
 */
class m180912_111803_add_ip_to_log extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('{{%log}}', 'ip', $this->integer()->unsigned()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%log}}', 'ip');
    }
}
