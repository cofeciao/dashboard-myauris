<?php

use yii\db\Migration;

/**
 * Class m200303_094731_alter_table_de_xuat_chi_delete_nguoi_de_xuat
 */
class m200303_094731_alter_table_de_xuat_chi_delete_nguoi_de_xuat extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200303_094731_alter_table_de_xuat_chi_delete_nguoi_de_xuat cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->dropColumn('thuchi_de_xuat_chi', 'nguoi_de_xuat');
    }

    public function down()
    {
        echo "m200303_094731_alter_table_de_xuat_chi_delete_nguoi_de_xuat cannot be reverted.\n";

        return false;
    }
}
