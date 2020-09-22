<?php

use yii\db\Migration;

/**
 * Class m200612_094407_dexuatchi_add_inspectioner
 */
class m200612_094407_dexuatchi_add_inspectioner extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$this->addColumn( 'thuchi_de_xuat_chi', 'inspectioner', $this->integer( 11 )->null() );
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200612_094407_dexuatchi_add_inspectioner cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200612_094407_dexuatchi_add_inspectioner cannot be reverted.\n";

		return false;
	}
	*/
}
