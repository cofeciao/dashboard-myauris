<?php

use yii\db\Migration;

/**
 * Class m200818_095144_remove_column_checkcode
 */
class m200818_095144_remove_column_checkcode extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('checkcode_bao_hanh', 'birth_date');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200818_095144_remove_column_checkcode cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200818_095144_remove_column_checkcode cannot be reverted.\n";

        return false;
    }
    */
}
