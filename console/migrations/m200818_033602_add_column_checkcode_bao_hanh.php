<?php

use yii\db\Migration;

/**
 * Class m200818_033602_add_column_checkcode_bao_hanh
 */
class m200818_033602_add_column_checkcode_bao_hanh extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('checkcode_bao_hanh', 'phong_kham_don_hang_w_order_id', $this->integer());
        $this->addColumn('checkcode_bao_hanh', 'phong_kham_don_hang_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200818_033602_add_column_checkcode_bao_hanh cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200818_033602_add_column_checkcode_bao_hanh cannot be reverted.\n";

        return false;
    }
    */
}
