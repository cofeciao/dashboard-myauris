<?php

use yii\db\Migration;

/**
 * Class m200616_023930_add_column_table_temp
 */
class m200616_023930_add_column_table_temp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('table_temp', 'nguoi_noi_tieng', $this->smallInteger()->defaultValue(0)->null()->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200616_023930_add_column_table_temp cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200616_023930_add_column_table_temp cannot be reverted.\n";

        return false;
    }
    */
}
