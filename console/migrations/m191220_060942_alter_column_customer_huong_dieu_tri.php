<?php

use yii\db\Migration;

/**
 * Class m191220_060942_alter_column_customer_huong_dieu_tri
 */
class m191220_060942_alter_column_customer_huong_dieu_tri extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('dep365_customer_online', 'customer_huong_dieu_tri', $this->text()->null());
        $this->alterColumn('dep365_customer_online', 'customer_ghichu_bacsi', $this->text()->null());
        $this->alterColumn('dep365_customer_online_bak', 'customer_huong_dieu_tri', $this->text()->null());
        $this->alterColumn('dep365_customer_online_bak', 'customer_ghichu_bacsi', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191220_060942_alter_column_customer_huong_dieu_tri cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191220_060942_alter_column_customer_huong_dieu_tri cannot be reverted.\n";

        return false;
    }
    */
}
