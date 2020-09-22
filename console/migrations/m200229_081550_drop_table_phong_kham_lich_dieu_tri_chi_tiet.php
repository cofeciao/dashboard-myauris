<?php

use yii\db\Migration;

/**
 * Class m200229_081550_drop_table_phong_kham_lich_dieu_tri_chi_tiet
 */
class m200229_081550_drop_table_phong_kham_lich_dieu_tri_chi_tiet extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('phong_kham_lich_dieu_tri_chi_tiet');
        echo "m200229_081550_drop_table_phong_kham_lich_dieu_tri_chi_tiet cannot be reverted.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
//        echo "m200229_081550_drop_table_phong_kham_lich_dieu_tri_chi_tiet cannot be reverted.\n";
//        if ($this->db->getTableSchema('phong_kham_lich_dieu_tri_chi_tiet', true) !== null) {
//            $this->dropTable('phong_kham_lich_dieu_tri_chi_tiet');
//        }
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200229_081550_drop_table_phong_kham_lich_dieu_tri_chi_tiet cannot be reverted.\n";

        return false;
    }
    */
}
