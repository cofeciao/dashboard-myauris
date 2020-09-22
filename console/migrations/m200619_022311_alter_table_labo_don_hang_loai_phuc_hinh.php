<?php

use yii\db\Migration;

/**
 * Class m200619_022311_alter_table_labo_don_hang_loai_phuc_hinh
 */
class m200619_022311_alter_table_labo_don_hang_loai_phuc_hinh extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('labo_don_hang', 'loai_phuc_hinh', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200619_022311_alter_table_labo_don_hang_loai_phuc_hinh cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200619_022311_alter_table_labo_don_hang_loai_phuc_hinh cannot be reverted.\n";

        return false;
    }
    */
}
