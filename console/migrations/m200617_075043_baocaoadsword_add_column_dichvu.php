<?php

use yii\db\Migration;

/**
 * Class m200617_075043_baocaoadsword_add_column_dichvu
 */
class m200617_075043_baocaoadsword_add_column_dichvu extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn( 'baocao_chay_adwords', 'product', 'string' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200617_075043_baocaoadsword_add_column_dichvu cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200617_075043_baocaoadsword_add_column_dichvu cannot be reverted.\n";

		return false;
	}
	*/
}
