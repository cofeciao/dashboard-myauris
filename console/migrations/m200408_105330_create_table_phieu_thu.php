<?php

use yii\db\Migration;

/**
 * Class m200408_105330_create_table_phieu_thu
 */
class m200408_105330_create_table_phieu_thu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('thuchi_phieuthu', [
            'id' => $this->primaryKey(),
            'title' => $this->text(),
            'nguoi_nop' => $this->integer(),
            'lydo_truongphong' => $this->text(),
            'lydo_nguoinop' => $this->text(),
            'so_tien' => $this->bigInteger(),
            'deadline' => $this->integer(),
            'ngay_nop' => $this->integer(),
            'truongphong_accepted' => $this->integer()->defaultValue(0),
            'status' => $this->tinyInteger(20),
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
        echo "\ cannot be reverted.\n";

        return false;
    }


}
