<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%phong_kham_lich_dieu_tri_chi_tiet}}`.
 */
class m200229_021500_create_phong_kham_lich_dieu_tri_chi_tiet_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('phong_kham_lich_dieu_tri_chi_tiet', [
            'id' => $this->primaryKey(),
            'phong_kham_lich_dieu_tri_id' => $this->integer(11)->null(),
            'user_id' => $this->integer(11)->null()->comment('Nguoi thuc hien thao tac'),
            'type_user' => $this->tinyInteger()
                ->null()
                ->defaultValue(0)
                ->comment('Quy dinh trong model lich dieu tri chi tiet'),
            'thao_tac' => $this->integer(11)->null()->comment('Quy dinh trong model lich dieu tri chi tiet'),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%phong_kham_lich_dieu_tri_chi_tiet}}');
    }
}
