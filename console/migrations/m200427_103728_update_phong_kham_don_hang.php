<?php

use yii\db\Migration;

/**
 * Class m200427_103728_update_phong_kham_don_hang
 */
class m200427_103728_update_phong_kham_don_hang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('phong_kham_don_hang', 'trang_thai_hoan_thanh', $this->integer()->null());
        $this->addColumn('phong_kham_don_hang_tree', 'trang_thai_hoan_thanh', $this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200427_103728_update_phong_kham_don_hang cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200427_103728_update_phong_kham_don_hang cannot be reverted.\n";

        return false;
    }
    */
}
