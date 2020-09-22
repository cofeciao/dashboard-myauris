<?php

use yii\db\Migration;

/**
 * Class m200311_104403_alter_thuchidexuat_so_tien_chi_integer_to_unsignedInteger
 */
class m200311_104403_alter_thuchidexuat_so_tien_chi_integer_to_unsignedInteger extends Migration
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
        echo "m200311_104403_alter_thuchidexuat_so_tien_chi_integer_to_unsignedInteger cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('thuchi_de_xuat_chi', 'so_tien_chi', $this->integer()->unsigned());
    }

    public function down()
    {
        echo "m200311_104403_alter_thuchidexuat_so_tien_chi_integer_to_unsignedInteger cannot be reverted.\n";

        return false;
    }
}
