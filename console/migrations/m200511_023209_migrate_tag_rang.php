<?php

use yii\db\Migration;

/**
 * Class m200511_023209_migrate_tag_rang
 */
class m200511_023209_migrate_tag_rang extends Migration
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
        $get_fields_tinh_trang_rang = Yii::$app->db->getTableSchema('tinh_trang_rang')->columns;
        if (!array_key_exists('js_bac_si', $get_fields_tinh_trang_rang)) {
            $this->addColumn('tinh_trang_rang', 'js_bac_si', $this->string(50)->null()->after('description'));
        }
        $get_fields_table_dental_tag = Yii::$app->db->getTableSchema('dep365_customer_online_dental_tag')->columns;
        if (!array_key_exists('table_name', $get_fields_table_dental_tag)) {
            $this->addColumn('dep365_customer_online_dental_tag', 'table_name', $this->string()->null()->after('id'));
        }
        $check_tinhtrangrang_tag = Yii::$app->db->getTableSchema('tinhtrangrang_tag_hasmany');
        if($check_tinhtrangrang_tag === null){
            $this->createTable('tinhtrangrang_tag_hasmany', [
                'tinh_trang_rang' => $this->integer(11)->notNull(),
                'tag' => $this->integer(11)->notNull(),
            ], $tableOptions);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200511_023209_migrate_tag_rang cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200511_023209_migrate_tag_rang cannot be reverted.\n";

        return false;
    }
    */
}
