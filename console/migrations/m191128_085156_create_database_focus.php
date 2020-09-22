<?php

use yii\db\Migration;

/**
 * Class m191128_085156_create_database_focus
 */
class m191128_085156_create_database_focus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%list_chup_focus_face}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'catagory_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ]);
        $this->addColumn('dep365_customer_images', 'id_loai_chup_hinh', $this->integer()->comment('Xác nhận hình này trên app thuộc loại nào')->null()->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->dropTable('{{%list_chup_focus_face}}');
    }
}
