<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%don_hang_bao_hanh}}`.
 */
class m200306_101236_create_don_hang_bao_hanh_table extends Migration
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
        $this->createTable('{{%don_hang_bao_hanh}}', [
            'id' => $this->primaryKey(),
            'phong_kham_don_hang_id' => $this->integer(11),
            'so_luong_rang' => $this->integer(11),
            'ly_do' => $this->string()->null(),
//            'bac_si_id' => $this->integer(11),
            'ngay_thuc_hien' => $this->integer(11)->null(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%don_hang_bao_hanh}}');
    }
}
