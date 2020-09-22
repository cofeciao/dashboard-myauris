<?php

use yii\db\Migration;

/**
 * Class m200428_042244_alter_dexuatchi_add_column_typedexuat_chosen_one
 */
class m200428_042244_alter_dexuatchi_add_column_typedexuat_chosen_one extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('thuchi_de_xuat_chi', 'type_dexuat', $this->string([255])->notNull());
        $this->addColumn('thuchi_de_xuat_chi', 'chosen_one', $this->integer(11)->notNull());
        $this->update('thuchi_de_xuat_chi', ['type_dexuat' => \backend\modules\chi\models\DeXuatChiModel::THANH_TOAN], '');
        $this->update('thuchi_de_xuat_chi', ['chosen_one' => 99], '');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200428_042244_alter_dexuatchi_add_column_typedexuat_chosen_one cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200428_042244_alter_dexuatchi_add_column_typedexuat_chosen_one cannot be reverted.\n";

        return false;
    }
    */
}
