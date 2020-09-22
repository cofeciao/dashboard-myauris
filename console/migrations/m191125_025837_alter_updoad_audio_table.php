<?php

use yii\db\Migration;

/**
 * Class m191125_025837_alter_updoad_audio_table
 */
class m191125_025837_alter_updoad_audio_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191125_025837_alter_updoad_audio_table cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('{{%upload_audio}}', 'created_by', $this->integer());
        $this->alterColumn('{{%upload_audio}}', 'updated_by', $this->integer());
    }
    /*

    public function down()
    {
        echo "m191125_025837_alter_updoad_audio_table cannot be reverted.\n";

        return false;
    }
    */
}
