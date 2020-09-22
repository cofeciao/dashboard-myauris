<?php

use yii\db\Migration;

/**
 * Class m200728_020421_add_column_tai_kham
 */
class m200728_020421_add_column_tai_kham extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* check column exists */
        $check_column = Yii::$app->db->getTableSchema('phong_kham_lich_dieu_tri')->columns;
        if (!array_key_exists('tai_kham', $check_column)) {
            $this->addColumn('phong_kham_lich_dieu_tri', 'tai_kham', $this->integer(11)->null()->after('is_danhgia')->comment('Lịch tái khám cho lịch điều trị nào'));
        }
        /* check column exists */
        $check_column = Yii::$app->db->getTableSchema('phong_kham_lich_dieu_tri_tree')->columns;
        if (!array_key_exists('tai_kham', $check_column)) {
            $this->addColumn('phong_kham_lich_dieu_tri_tree', 'tai_kham', $this->integer(11)->null()->after('is_danhgia')->comment('Lịch tái khám cho lịch điều trị nào'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200728_020421_add_column_tai_kham cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200728_020421_add_column_tai_kham cannot be reverted.\n";

        return false;
    }
    */
}
