<?php

use yii\db\Migration;

/**
 * Class m200511_064836_alter_table_labo_dondang
 */
class m200511_064836_alter_table_labo_dondang extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('labo_don_hang', 'vi_tri_rang', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200511_064836_alter_table_labo_dondang cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200511_064836_alter_table_labo_dondang cannot be reverted.\n";

        return false;
    }
    */
}
