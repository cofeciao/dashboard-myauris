<?php

use yii\db\Migration;

/**
 * Class m200617_071610_delete_column_baocao_adsword
 */
class m200617_071610_delete_column_baocao_adsword extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->dropColumn( 'baocao_chay_adwords', 'appearance' );
		$this->dropColumn( 'baocao_chay_adwords', 'click' );
		$this->dropColumn( 'baocao_chay_adwords', 'ctr' );
		$this->dropColumn( 'baocao_chay_adwords', 'cpc' );
		$this->dropColumn( 'baocao_chay_adwords', 'cpv' );
		$this->dropColumn( 'baocao_chay_adwords', 'views' );
		$this->dropColumn( 'baocao_chay_adwords', 'zalo' );
		$this->dropColumn( 'baocao_chay_adwords', 'mess' );
		$this->dropColumn( 'baocao_chay_adwords', 'amount_phone' );
		$this->dropColumn( 'baocao_chay_adwords', 'amount_form' );
		$this->dropColumn( 'baocao_chay_adwords', 'amount_call' );
		$this->dropColumn( 'baocao_chay_adwords', 'keywords' );
		$this->dropColumn( 'baocao_chay_adwords', 'channels' );
		$this->dropColumn( 'baocao_chay_adwords', 'location' );
		$this->dropColumn( 'baocao_chay_adwords', 'list_links' );
		$this->dropColumn( 'baocao_chay_adwords', 'view_rate' );
		$this->dropColumn( 'baocao_chay_adwords', 'post_type' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200617_071610_delete_column_baocao_adsword cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200617_071610_delete_column_baocao_adsword cannot be reverted.\n";

		return false;
	}
	*/
}
