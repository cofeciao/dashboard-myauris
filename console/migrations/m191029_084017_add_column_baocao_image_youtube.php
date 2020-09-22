<?php

use yii\db\Migration;

/**
 * Class m191029_084017_add_column_baocao_image_youtube
 */
class m191029_084017_add_column_baocao_image_youtube extends Migration
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
        echo "m191029_084017_add_column_baocao_image_youtube cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('{{%baocao_chay_adwords}}', 'image_list', $this->text());
        $this->addColumn('{{%baocao_chay_adwords}}', 'youtube_list', $this->text());
    }

    public function down()
    {
        echo "m191029_084017_add_column_baocao_image_youtube cannot be reverted.\n";

        return false;
    }
}
