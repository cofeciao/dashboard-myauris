<?php

use yii\db\Migration;

/**
 * Class m191128_032635_add_column_room_id_table_phong_kham_lich_dieu_tri
 */
class m191128_032635_add_column_room_id_table_phong_kham_lich_dieu_tri_tree extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('phong_kham_lich_dieu_tri_tree', 'room_id', $this->integer(11)->null()->after('note'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191128_032635_add_column_room_id_table_phong_kham_lich_dieu_tri cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191128_032635_add_column_room_id_table_phong_kham_lich_dieu_tri cannot be reverted.\n";

        return false;
    }
    */
}
