<?php

use yii\db\Migration;

/**
 * Class m200814_101037_add_column_don_hang
 */
class m200814_101037_add_column_don_hang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('phong_kham_don_hang', 'trang_thai_dich_vu', $this->smallInteger()->defaultValue(0)->null()->comment('trang thai hoan thanh dich vu dua vao lich dieu tri cuoi 0,1'));
        $this->addColumn('phong_kham_don_hang_tree', 'trang_thai_dich_vu', $this->smallInteger()->defaultValue(0)->null()->comment('trang thai hoan thanh dich vu dua vao lich dieu tri cuoi 0,1'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('phong_kham_don_hang', 'trang_thai_dich_vu');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200814_101037_add_column_don_hang cannot be reverted.\n";

        return false;
    }
    */
}
