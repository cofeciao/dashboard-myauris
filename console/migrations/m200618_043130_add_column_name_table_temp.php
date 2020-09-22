<?php

use yii\db\Migration;

/**
 * Class m200618_043130_add_column_name_table_temp
 */
class m200618_043130_add_column_name_table_temp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('table_temp', 'name', $this->string(64)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200618_043130_add_column_name_table_temp cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200618_043130_add_column_name_table_temp cannot be reverted.\n";

        return false;
    }
    */
}
