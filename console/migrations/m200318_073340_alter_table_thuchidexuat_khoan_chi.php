<?php

use yii\db\Migration;

/**
 * Class m200318_073340_alter_table_thuchidexuat_khoan_chi
 */
class m200318_073340_alter_table_thuchidexuat_khoan_chi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('thuchi_de_xuat_chi', 'khoan_chi', $this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200318_073340_alter_table_thuchidexuat_khoan_chi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200318_073340_alter_table_thuchidexuat_khoan_chi cannot be reverted.\n";

        return false;
    }
    */
}
