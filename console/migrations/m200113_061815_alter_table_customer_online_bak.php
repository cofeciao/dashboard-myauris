<?php

use yii\db\Migration;

/**
 * Class m200113_061815_alter_table_customer_online_bak
 */
class m200113_061815_alter_table_customer_online_bak extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("dep365_customer_online_bak", "note_tinh_trang_kh", $this->text()->null());
        $this->addColumn("dep365_customer_online_bak", "note_mong_muon_kh", $this->text()->null());
        $this->addColumn("dep365_customer_online_bak", "note_direct_sale_ho_tro", $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200113_061815_alter_table_customer_online_bak cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200113_061815_alter_table_customer_online_bak cannot be reverted.\n";

        return false;
    }
    */
}
