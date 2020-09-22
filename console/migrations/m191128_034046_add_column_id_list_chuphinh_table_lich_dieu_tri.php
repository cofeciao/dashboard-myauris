<?php

use yii\db\Migration;

/**
 * Class m191128_034046_add_column_id_list_chuphinh_table_lich_dieu_tri
 */
class m191128_034046_add_column_id_list_chuphinh_table_lich_dieu_tri extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('phong_kham_lich_dieu_tri', 'id_list_chuphinh', $this->integer(11)->null()->after('note'));
        $this->addColumn('phong_kham_lich_dieu_tri_tree', 'id_list_chuphinh', $this->integer(11)->null()->after('note'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191128_034046_add_column_id_list_chuphinh_table_lich_dieu_tri cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191128_034046_add_column_id_list_chuphinh_table_lich_dieu_tri cannot be reverted.\n";

        return false;
    }
    */
}
