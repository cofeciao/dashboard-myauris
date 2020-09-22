<?php

use yii\db\Migration;

/**
 * Class m191127_074059_update_database_with_windsoft
 */
class m191127_074059_update_database_with_windsoft extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'device_id', $this->string(255)->null()->after('team'));
        $this->addColumn('phong_kham_lich_dieu_tri', 'room_id', $this->integer(11)->after('note'));
        $this->createTable('{{%phong_kham_thong_bao}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
            'content' => $this->text()->null(),
            'type' => $this->integer(11)->notNull(),
            'created_at' => $this->integer(11),
            'user_id' => $this->integer()->notNull(),
            'customer_id' => $this->integer(),
        ]);
    }

    public function safeDown()
    {
        echo "m191127_074059_update_database_with_windsoft cannot be reverted.\n";
        return false;
    }
}
