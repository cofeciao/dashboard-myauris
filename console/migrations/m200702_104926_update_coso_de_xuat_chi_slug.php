<?php

use yii\db\Migration;

/**
 * Class m200702_104926_update_coso_de_xuat_chi_slug
 */
class m200702_104926_update_coso_de_xuat_chi_slug extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->update( 'thuchi_de_xuat_chi', [ 'coso' => 'head-office' ], [ 'coso' => '0' ] );
		$this->update( 'thuchi_de_xuat_chi', [ 'coso' => 'co-so-2' ], [ 'coso' => '1' ] );
		$this->update( 'thuchi_de_xuat_chi', [ 'coso' => 'co-so-1' ], [ 'coso' => '2' ] );
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200702_104926_update_coso_de_xuat_chi_slug cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200702_104926_update_coso_de_xuat_chi_slug cannot be reverted.\n";

		return false;
	}
	*/
}
