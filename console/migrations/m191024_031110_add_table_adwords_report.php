<?php

use yii\db\Migration;

/**
 * Class m191024_031110_add_table_adwords_report
 */
class m191024_031110_add_table_adwords_report extends Migration
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
        echo "m191024_031110_add_table_adwords_report cannot be reverted.\n";

        return false;
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%baocao_chay_adwords}}', [
            'id'           => $this->primaryKey(),
            'post_type'    => $this->string(50)->notNull(),
            'amount_money' => $this->decimal(65),
            'appearance'   => $this->integer(),
            'click'        => $this->integer(),
            'ctr'          => $this->decimal(),
            'cpc'          => $this->integer(),
            'cpv'          => $this->integer(),

            'views'        => $this->integer(),
            'zalo'         => $this->integer(),
            'mess'         => $this->integer(),
            'amount_phone' => $this->integer(),
            'amount_form'  => $this->integer(),
            'amount_call'  => $this->integer(),

            'keywords' => $this->text(),
            'channels' => $this->text(),

            'location'   => $this->decimal(),
            'list_links' => $this->text(),
            'view_rate'  => $this->decimal(),
            'status'     => $this->smallInteger()->notNull()->defaultValue(10),
            'ngay_tao'   => $this->integer()->notNull()->defaultValue(time()),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%baocao_chay_adwords}}');
    }
}
