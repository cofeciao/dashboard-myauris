<?php

use yii\db\Migration;

/**
 * Class m200515_095709_add_column_co_so_thuchi_dexuatchi
 */
class m200515_095709_add_column_co_so_thuchi_dexuatchi extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn( 'thuchi_de_xuat_chi', 'coso', $this->tinyInteger()->null() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200515_095709_add_column_co_so_thuchi_dexuatchi cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200515_095709_add_column_co_so_thuchi_dexuatchi cannot be reverted.\n";

		return false;
	}
	*/
}
