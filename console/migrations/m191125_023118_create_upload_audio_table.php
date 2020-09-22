<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%upload_audio}}`.
 */
class m191125_023118_create_upload_audio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%upload_audio}}', [
            'id' => $this->primaryKey(),
            'customer_id'   => $this->integer()->notNull(),
            'folder_id'   => $this->string()->notNull(),
            'status'     => $this->smallInteger()->notNull()->defaultValue(1),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%upload_audio}}');
    }
}
