<?php

use yii\db\Migration;

/**
 * Class m200703_081421_update_coso_thuchi_de_xuat_json
 */
class m200703_081421_update_coso_thuchi_de_xuat_json extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$sql = <<<SQL
		update thuchi_de_xuat_chi set coso= concat ('["',coso,'"]') where coso is not null and json_valid(coso)=0;
SQL;
		$this->execute( $sql );
		$this->alterColumn( 'thuchi_de_xuat_chi', 'coso', $this->json()->null()->defaultValue( null ) );

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200703_081421_update_coso_thuchi_de_xuat_json cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200703_081421_update_coso_thuchi_de_xuat_json cannot be reverted.\n";

		return false;
	}
	*/
}
