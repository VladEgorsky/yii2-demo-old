<?php

use yii\db\Migration;

/**
 * Class m180922_060835_add_column_class_to_template
 */
class m180922_060835_add_column_class_to_template extends Migration
{
    public function up()
    {
        $this->addColumn('{{%template}}', 'items_classes', $this->string()->unsigned()->notNull());
        $this->alterColumn('{{%template_location}}', 'location_key', $this->string(15)->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%template}}', 'items_classes');
    }
}
