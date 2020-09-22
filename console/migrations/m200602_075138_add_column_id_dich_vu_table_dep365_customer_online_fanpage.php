<?php

use yii\db\Migration;

/**
 * Class m200602_075138_add_column_id_dich_vu_table_dep365_customer_online_fanpage
 */
class m200602_075138_add_column_id_dich_vu_table_dep365_customer_online_fanpage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dep365_customer_online_fanpage', 'id_dich_vu', $this->integer()->notNull()->after('mota'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200602_075138_add_column_id_dich_vu_table_dep365_customer_online_fanpage cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200602_075138_add_column_id_dich_vu_table_dep365_customer_online_fanpage cannot be reverted.\n";

        return false;
    }
    */
}
