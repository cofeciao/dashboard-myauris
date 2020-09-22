<?php

use yii\db\Migration;

/**
 * Class m200807_025016_add_column_table_phong_kham_don_hang
 */
class m200807_025016_add_column_table_phong_kham_don_hang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('phong_kham_don_hang', 'confirm', $this->smallInteger()->defaultValue(0)->comment('Ke toan xac nhan doanh so don hang- 1: xac nhan'));
        $this->addColumn('phong_kham_don_hang', 'confirm_by', $this->integer(11)->comment('nguoi xac nha don hang'));
        $this->addColumn('phong_kham_don_hang', 'confirm_at', $this->integer(11)->comment('thoi gian xac nhan doanh so'));

        $this->addColumn('phong_kham_don_hang_tree', 'confirm', $this->smallInteger()->defaultValue(0)->comment('Ke toan xac nhan doanh so don hang - 1: xac nhan'));
        $this->addColumn('phong_kham_don_hang_tree', 'confirm_by', $this->integer(11)->comment('nguoi xac nha don hang'));
        $this->addColumn('phong_kham_don_hang_tree', 'confirm_at', $this->integer(11)->comment('thoi gian xac nhan doanh so'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200807_025016_add_column_table_phong_kham_don_hang cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200807_025016_add_column_table_phong_kham_don_hang cannot be reverted.\n";

        return false;
    }
    */
}
