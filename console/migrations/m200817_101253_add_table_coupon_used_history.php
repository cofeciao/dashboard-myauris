<?php

use yii\db\Migration;

/**
 * Class m200817_101253_add_table_coupon_used_history
 */
class m200817_101253_add_table_coupon_used_history extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->createTable( 'coupon_used_history', [
			'coupon_code'      => $this->string( 255 )->notNull(),
			'orderitem_id_api' => $this->integer()->notNull(),
			'customer_id_api'  => $this->integer()->notNull(),
			'order_id_api'     => $this->integer()->notNull(),
			'order_id'         => $this->integer()->notNull(),
			'giaban'           => $this->integer()->notNull(),
			'giamua'           => $this->integer()->notNull(),
			'created_at'       => $this->integer(),
			'created_by'       => $this->integer(),
		] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200817_101253_add_table_coupon_used_history cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200817_101253_add_table_coupon_used_history cannot be reverted.\n";

		return false;
	}
	*/
}
