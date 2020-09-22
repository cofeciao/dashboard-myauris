<?php

use yii\db\Migration;

/**
 * Class m200311_100618_delete_column_dexuatchi_ndchi
 */
class m200311_100618_delete_column_dexuatchi_ndchi extends Migration
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
        echo "m200311_100618_delete_column_dexuatchi_ndchi cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->dropColumn('thuchi_de_xuat_chi', 'noi_dung_chi');
        $this->addColumn('thuchi_deadline', 'id_tieu_chi', $this->integer(11));
        $this->dropColumn('thuchi_deadline', 'id_de_xuat_chi');
        $this->dropColumn('thuchi_deadline', 'danh_gia');
        $this->addColumn('thuchi_tieu_chi', 'status', $this->boolean()->defaultValue(0));
    }

    public function down()
    {
        echo "m200311_100618_delete_column_dexuatchi_ndchi cannot be reverted.\n";

        return false;
    }
}
