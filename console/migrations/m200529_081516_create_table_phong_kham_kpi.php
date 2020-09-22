<?php

use yii\db\Migration;

/**
 * Class m200529_081516_create_table_phong_kham_kpi
 */
class m200529_081516_create_table_phong_kham_kpi extends Migration
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
        $this->createTable('{{%phong_kham_kpi}}', [
            'id' => $this->primaryKey(),
            'kpi_tuong_tac'   => $this->integer()->notNull(),
            'kpi_lich_hen'   => $this->integer()->notNull(),
            'kpi_lich_moi'   => $this->integer()->notNull(),
            'kpi_khach_den'   => $this->integer()->notNull(),
            'kpi_khach_lam'   => $this->integer()->notNull(),
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
        echo "m200529_081516_create_table_phong_kham_kpi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200529_081516_create_table_phong_kham_kpi cannot be reverted.\n";

        return false;
    }
    */
}
