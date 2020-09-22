<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%}}`.
 */
class m200321_024456_drop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{tuyendung}}');
        $this->dropTable('{{tuyendung_job}}');
        $this->dropTable('{{tuyendung_regime}}');
        $this->dropTable('{{test}}');
        $this->dropTable('{{tag_seo}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
