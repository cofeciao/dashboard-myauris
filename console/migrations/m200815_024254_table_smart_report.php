<?php

use yii\db\Migration;

/**
 * Class m200815_024254_table_smart_report
 */
class m200815_024254_table_smart_report extends Migration
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
        $check_table_smart_report = Yii::$app->db->getTableSchema('smart_report');
        if ($check_table_smart_report === null) {
            $this->createTable('smart_report', [
                'id' => $this->primaryKey(),
                'id_khoan_chi' => $this->smallInteger()->notNull(),
                'tien_da_chi' => $this->integer()->null()->defaultValue(0),
                'tien_cho_duyet' => $this->integer()->null()->defaultValue(0),
                'report_timestamp' => $this->integer()->notNull(),
                'status'     => $this->smallInteger()->notNull()->defaultValue(1),
                'created_by' => $this->integer()->null(),
                'updated_by' => $this->integer()->null(),
                'created_at' => $this->integer()->null(),
                'updated_at' => $this->integer()->null(),
            ], $tableOptions);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200815_024254_table_smart_report cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200815_024254_table_smart_report cannot be reverted.\n";

        return false;
    }
    */
}
