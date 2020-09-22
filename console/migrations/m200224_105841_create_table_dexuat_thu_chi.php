<?php

use yii\db\Migration;

/**
 * Class m200224_105841_create_table_dexuat_thu_chi
 */
class m200224_105841_create_table_dexuat_thu_chi extends Migration
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
        echo "m200224_105841_create_table_dexuat_thu_chi cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('thuchi_de_xuat_chi', [
            'id' => $this->primaryKey(),
            'nguoi_de_xuat' => $this->string(255)->notNull(),
            'nguoi_trien_khai' => $this->string(255)->notNull(),
            'so_tien_chi' => $this->integer(11)->notNull()->comment('Loại đánh giá'),
            'khoan_chi' => $this->integer(11)->notNull()->comment('Khoản chi'),
            'noi_dung_chi' => $this->text()->notNull()->comment('Nội Dung Chi'),
            'thoi_han_thanh_toan' => $this->integer(11)->notNull()->comment('Loại đánh giá'),
            'status' => $this->tinyInteger()
                ->null()
                ->defaultValue(0)
                ->comment('0: Đang đợi duyệt,1: Trưởng phòng đã duyệt,2: Không được duyệt,3: Kế toán đã duyệt,4: Hoàn thành,5: Hoàn tiền,6: Huỷ đề xuất'),
            'leader_accept' => $this->integer(11)->null(),
            'leader_accept_at' => $this->integer(11)->null(),
            'accountant_accept' => $this->integer(11)->null(),
            'accountant_accept_at' => $this->integer(11)->null(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);
        $this->createTable('thuchi_deadline', [
            'id' => $this->primaryKey(),
            'id_de_xuat_chi' => $this->integer(11)->notNull(),
            'thoi_gian_bat_dau' => $this->integer(11)->null(),
            'thoi_gian_ket_thuc' => $this->integer(11)->null(),
            'danh_gia' => $this->text()->null(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
        ], $tableOptions);
        $this->createTable('thuchi_ho_so', [
            'id' => $this->primaryKey(),
            'id_de_xuat_chi' => $this->integer(11)->notNull(),
            'file' => $this->string(255)->notNull(),
            'status' => $this->boolean()
                ->null()
                ->defaultValue(1)
                ->comment('0: Đã xóa,1: Đang hiển thị'),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);
        $this->createTable('thuchi_comment', [
            'id' => $this->primaryKey(),
            'id_de_xuat_chi' => $this->integer(11)->notNull(),
            'comment' => $this->text()->notNull(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
        ], $tableOptions);
    }

    public function down()
    {
        echo "m200224_105841_create_table_dexuat_thu_chi cannot be reverted.\n";

        return false;
    }
}
