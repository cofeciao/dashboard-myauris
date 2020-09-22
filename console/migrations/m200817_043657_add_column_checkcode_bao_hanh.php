<?php

use yii\db\Migration;

/**
 * Class m200817_043657_add_column_checkcode_bao_hanh
 */
class m200817_043657_add_column_checkcode_bao_hanh extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('checkcode_bao_hanh', 'product_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200817_043657_add_column_checkcode_bao_hanh cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200817_043657_add_column_checkcode_bao_hanh cannot be reverted.\n";

        return false;
    }
    */
}
