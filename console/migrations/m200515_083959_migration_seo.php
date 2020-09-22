<?php

use yii\db\Migration;

/**
 * Class m200515_083959_migration_seo
 */
class m200515_083959_migration_seo extends Migration
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
        $check_analytics_log = Yii::$app->db->getTableSchema('myauris_analytics_log');
        if($check_analytics_log === null){
            $this->createTable('myauris_analytics_log', [
                'id' => $this->primaryKey(),
                'from_url' => $this->string(255)->null()->defaultValue('')->comment("null - direct link"),
                'referer_url' => $this->string(255)->null()->defaultValue('')->comment("null - referer link"),
                'first_url' => $this->string(255)->null()->defaultValue('')->comment('first url customer connect'),
                'call_url' => $this->string(255)->null()->defaultValue('')->comment('url customer click button call'),
                'time' => $this->integer(11)->null(),
                'cookie_user_id' => $this->string(255)->notNull()->comment('cookie user id of customer on myauris'),
                'device_info' => $this->text()->null(),
                'phone' => $this->string(25)->null()->defaultValue(null)->comment('phone of customer'),
                'created_at' => $this->integer(11)->null()
            ], $tableOptions);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200515_083959_migration_seo cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200515_083959_migration_seo cannot be reverted.\n";

        return false;
    }
    */
}
