<?php

use yii\db\Migration;

/**
 * Class m200818_085948_add_column_checkcode_bao_hanh
 */
class m200818_085948_add_column_checkcode_bao_hanh extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('checkcode_bao_hanh', 'customer_name', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200818_085948_add_column_checkcode_bao_hanh cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200818_085948_add_column_checkcode_bao_hanh cannot be reverted.\n";

        return false;
    }
    */
}
