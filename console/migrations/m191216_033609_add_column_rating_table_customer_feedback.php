<?php

use yii\db\Migration;

/**
 * Class m191216_033609_add_column_rating_table_customer_feedback
 */
class m191216_033609_add_column_rating_table_customer_feedback extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('customer_feedback', 'rating', $this->integer()->notNull()->after('token_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191216_033609_add_column_rating_table_customer_feedback cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191216_033609_add_column_rating_table_customer_feedback cannot be reverted.\n";

        return false;
    }
    */
}
