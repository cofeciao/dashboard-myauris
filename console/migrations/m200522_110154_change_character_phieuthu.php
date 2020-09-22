<?php

use yii\db\Migration;

/**
 * Class m200522_110154_change_character_phieuthu
 */
class m200522_110154_change_character_phieuthu extends Migration {
	/**
	 * {@inheritdoc}
	 */
	public function safeUp() {
		$db = Yii::$app->getDb();

		$db->createCommand( "alter table thuchi_phieuthu convert to character set utf8 collate utf8_unicode_ci"  )->execute();
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown() {
		echo "m200522_110154_change_character_phieuthu cannot be reverted.\n";

		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200522_110154_change_character_phieuthu cannot be reverted.\n";

		return false;
	}
	*/
}
