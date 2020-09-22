<?php

use yii\db\Migration;

/**
 * Class m191113_045505_table_dep365_customer_online_add_column_nguoi_gioi_thieu
 */
class m191113_045505_table_dep365_customer_online_add_column_nguoi_gioi_thieu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dep365_customer_online', 'nguoi_gioi_thieu', $this->integer()->null()->after('id_dich_vu')->comment('Người giới thiệu'));
        $this->addColumn('dep365_customer_online_bak', 'nguoi_gioi_thieu', $this->integer()->null()->after('id_dich_vu')->comment('Người giới thiệu'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191113_045505_table_dep365_customer_online_add_column_nguoi_gioi_thieu cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191113_045505_table_dep365_customer_online_add_column_nguoi_gioi_thieu cannot be reverted.\n";

        return false;
    }
    */
}
