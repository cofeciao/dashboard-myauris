<?php

use yii\db\Migration;

/**
 * Class m200425_065326_alter_thuchi_khoan_chi_from_number_to_varchar
 */
class m200425_065326_alter_thuchi_khoan_chi_from_number_to_varchar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('thuchi_de_xuat_chi', 'khoan_chi', $this->text()->defaultValue(null)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200425_065326_alter_thuchi_khoan_chi_from_number_to_varchar cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200425_065326_alter_thuchi_khoan_chi_from_number_to_varchar cannot be reverted.\n";

        return false;
    }
    */
}
