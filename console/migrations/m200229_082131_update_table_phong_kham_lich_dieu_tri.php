<?php

use yii\db\Migration;

/**
 * Class m200229_082131_update_table_phong_kham_lich_dieu_tri
 */
class m200229_082131_update_table_phong_kham_lich_dieu_tri extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('phong_kham_lich_dieu_tri', 'thao_tac', $this->json()->null());
        $this->addColumn('phong_kham_lich_dieu_tri_tree', 'thao_tac', $this->json()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200229_082131_update_table_phong_kham_lich_dieu_tri cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200229_082131_update_table_phong_kham_lich_dieu_tri cannot be reverted.\n";

        return false;
    }
    */
}
