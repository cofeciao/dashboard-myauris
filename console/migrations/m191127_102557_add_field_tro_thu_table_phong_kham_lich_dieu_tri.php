<?php

use yii\db\Migration;

/**
 * Class m191127_102557_add_field_tro_thu_table_phong_kham_lich_dieu_tri
 */
class m191127_102557_add_field_tro_thu_table_phong_kham_lich_dieu_tri extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('phong_kham_lich_dieu_tri', 'tro_thu', $this->json()->null()->after('ekip'));
        $this->addColumn('phong_kham_lich_dieu_tri_tree', 'tro_thu', $this->json()->null()->after('ekip'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191127_102557_add_field_tro_thu_table_phong_kham_lich_dieu_tri cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191127_102557_add_field_tro_thu_table_phong_kham_lich_dieu_tri cannot be reverted.\n";

        return false;
    }
    */
}
