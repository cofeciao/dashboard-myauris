<?php

use yii\db\Migration;

/**
 * Class m200702_102818_update_coso_dexuatchi
 */
class m200702_102818_update_coso_dexuatchi extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->alterColumn( 'thuchi_de_xuat_chi', 'coso', $this->string( 50 ) );

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200702_102818_update_coso_dexuatchi cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200702_102818_update_coso_dexuatchi cannot be reverted.\n";

		return false;
	}
	*/
}
