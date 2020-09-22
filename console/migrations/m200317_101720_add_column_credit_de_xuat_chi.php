<?php

use yii\db\Migration;

/**
 * Class m200317_101720_add_column_credit_de_xuat_chi
 */
class m200317_101720_add_column_credit_de_xuat_chi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200317_101720_add_column_credit_de_xuat_chi cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('thuchi_de_xuat_chi', 'receiver', $this->string(255));
        $this->addColumn('thuchi_de_xuat_chi', 'receiver_phone', $this->string(15));
        $this->addColumn('thuchi_de_xuat_chi', 'method_payment', $this->string(255));
        $this->addColumn('thuchi_de_xuat_chi', 'owner_credit_name', $this->string(255));
        $this->addColumn('thuchi_de_xuat_chi', 'credit_number', $this->string(20));
        $this->addColumn('thuchi_de_xuat_chi', 'banking_name', $this->string(255));
    }

    public function down()
    {
        echo "m200317_101720_add_column_credit_de_xuat_chi cannot be reverted.\n";

        return false;
    }
}
