<?php

use yii\db\Migration;

/**
 * Class m200106_100035_alter_field_answer_null
 */
class m200106_100035_alter_field_answer_null extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("support", "anwser", $this->text()->null()->after("question"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200106_100035_alter_field_answer_null cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200106_100035_alter_field_answer_null cannot be reverted.\n";

        return false;
    }
    */
}
