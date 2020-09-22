<?php

use yii\db\Migration;

/**
 * Class m200113_032508_add_column_time_table_support
 */
class m200113_032508_add_column_time_table_support extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('support', 'time', $this->float()->after('status')->comment('Thời gian đọc câu trả lời'));
        $this->addColumn('support', 'users_view', $this->json()->after('time')->comment('Users đã xem câu trả lời'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200113_032508_add_column_time_table_support cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200113_032508_add_column_time_table_support cannot be reverted.\n";

        return false;
    }
    */
}
