<?php

use yii\db\Migration;

/**
 * Class m200520_084127_add_column_don_hang_labo
 */
class m200520_084127_add_column_don_hang_labo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('labo_don_hang', 'user_labo', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200520_084127_add_column_don_hang_labo cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200520_084127_add_column_don_hang_labo cannot be reverted.\n";

        return false;
    }
    */
}
