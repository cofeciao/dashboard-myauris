<?php

use yii\db\Migration;

/**
 * Class m200703_084045_alter_column_post_type_table_baocao_chay_adwords
 */
class m200703_084045_alter_column_post_type_table_baocao_chay_adwords extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* check column exists */
        $get_fields_baocao_adwords= Yii::$app->db->getTableSchema('baocao_chay_adwords')->columns;
        if (array_key_exists('post_type', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'post_type');
        }
        if (array_key_exists('appearance', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'appearance');
        }
        if (array_key_exists('click', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'click');
        }
        if (array_key_exists('ctr', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'ctr');
        }
        if (array_key_exists('cpc', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'cpc');
        }
        if (array_key_exists('cpv', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'cpv');
        }
        if (array_key_exists('views', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'views');
        }
        if (array_key_exists('mess', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'mess');
        }
        if (array_key_exists('zalo', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'zalo');
        }
        if (array_key_exists('amount_phone', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'amount_phone');
        }
        if (array_key_exists('amount_form', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'amount_form');
        }
        if (array_key_exists('amount_call', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'amount_call');
        }
        if (array_key_exists('keywords', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'keywords');
        }
        if (array_key_exists('channels', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'channels');
        }
        if (array_key_exists('location', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'location');
        }
        if (array_key_exists('list_links', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'list_links');
        }
        if (array_key_exists('view_rate', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'view_rate');
        }
        if (array_key_exists('image_list', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'image_list');
        }
        if (array_key_exists('youtube_list', $get_fields_baocao_adwords)) {
            $this->dropColumn('baocao_chay_adwords', 'youtube_list');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200703_084045_alter_column_post_type_table_baocao_chay_adwords cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200703_084045_alter_column_post_type_table_baocao_chay_adwords cannot be reverted.\n";

        return false;
    }
    */
}
