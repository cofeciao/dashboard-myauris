<?php

use yii\db\Migration;

/**
 * Class m200529_102704_convert_sotienchi_dexuatchi_int_to_varchar
 */
class m200529_102704_convert_sotienchi_dexuatchi_int_to_varchar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    	$this->alterColumn('thuchi_de_xuat_chi','so_tien_chi',$this->string(30));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200529_102704_convert_sotienchi_dexuatchi_int_to_varchar cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200529_102704_convert_sotienchi_dexuatchi_int_to_varchar cannot be reverted.\n";

        return false;
    }
    */
}
