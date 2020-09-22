<?php

use yii\db\Migration;

/**
 * Class m200818_071445_alter_column_coupon_used_history
 */
class m200818_071445_alter_column_coupon_used_history extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn( 'coupon_used_history', 'coupon_name', $this->string()->null() );
		$this->addColumn( 'coupon_used_history', 'customer_name', $this->string()->null() );
		$this->addColumn( 'coupon_used_history', 'phone', $this->string()->null() );
		$this->addColumn( 'coupon_used_history', 'email', $this->string()->null() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200818_071445_alter_column_coupon_used_history cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200818_071445_alter_column_coupon_used_history cannot be reverted.\n";

		return false;
	}
	*/
}
