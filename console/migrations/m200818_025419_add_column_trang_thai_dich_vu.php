<?php

use yii\db\Migration;

/**
 * Class m200818_025419_add_column_trang_thai_dich_vu
 */
class m200818_025419_add_column_trang_thai_dich_vu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* check column exists */
        $check_column = Yii::$app->db->getTableSchema('phong_kham_don_hang_tree')->columns;
        if (!array_key_exists('trang_thai_dich_vu', $check_column)) {
            $this->addColumn('phong_kham_don_hang_tree', 'trang_thai_dich_vu', $this->smallInteger()->defaultValue(0)->null()->comment('trang thai hoan thanh dich vu dua vao lich dieu tri cuoi 0,1'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('phong_kham_don_hang_tree', 'trang_thai_dich_vu');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200818_025419_add_column_trang_thai_dich_vu cannot be reverted.\n";

        return false;
    }
    */
}
