<?php

use yii\db\Migration;

/**
 * Class m200615_092505_dexuatchi_update_inspectioner
 */
class m200615_092505_dexuatchi_update_inspectioner extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		foreach (
			( new \yii\db\Query() )->from( 'thuchi_de_xuat_chi' )->select( [
				'leader_accept',
				'id'
			] )->each() as $val
		) {
			$this->update( 'thuchi_de_xuat_chi', [ 'inspectioner' => $val['leader_accept'] ], [ 'id' => $val['id'] ] );
		}

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200615_092505_dexuatchi_update_inspectioner cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200615_092505_dexuatchi_update_inspectioner cannot be reverted.\n";

		return false;
	}
	*/
}
