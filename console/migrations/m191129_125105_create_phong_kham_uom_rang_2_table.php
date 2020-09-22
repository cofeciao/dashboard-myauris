<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%phong_kham_uom_rang_2}}`.
 */
class m191129_125105_create_phong_kham_uom_rang_2_table extends Migration
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
        $this->createTable('{{%phong_kham_uom_rang_2}}', [
            'id' => $this->primaryKey(),
            'customer_id'   => $this->integer()->notNull(),
            'folder_id'   => $this->string()->notNull(),
            'status'     => $this->smallInteger()->notNull()->defaultValue(1),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%phong_kham_uom_rang_2}}');
    }
}
