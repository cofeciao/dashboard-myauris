<?php

use yii\db\Migration;

/**
 * Class m200207_034511_add_column_ngay_dong_y_lam
 */
class m200207_034511_add_column_ngay_dong_y_lam extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dep365_customer_online', 'ngay_dong_y_lam', $this->integer()->null()->after('customer_come_time_to')->comment('Ngày khách đồng ý làm lần đầu'));
        $this->addColumn('dep365_customer_online_bak', 'ngay_dong_y_lam', $this->integer()->null()->after('customer_come_time_to')->comment('Ngày khách đồng ý làm lần đầu'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200207_034511_add_column_ngay_dong_y_lam cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200207_034511_add_column_ngay_dong_y_lam cannot be reverted.\n";

        return false;
    }
    */
}
