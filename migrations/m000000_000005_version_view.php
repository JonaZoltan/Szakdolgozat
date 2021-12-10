<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.04.23., 10:44:42
 * The used disentanglement, and any part of the code
 * m200423_104400_version_view.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m000000_000005_version_view extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropTable('version_view');

		$this->createTable('version_view', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(11)->defaultValue(null),
			'version_id' => $this->integer(11)->defaultValue(null),
			'when' => $this->dateTime()->defaultValue(null),
		]);

		$this->addForeignKey('u_id_fk', 'version_view', 'user_id', 'user', 'id');
		$this->addForeignKey('v_h_id_fk', 'version_view', 'version_id', 'version_history', 'id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m200423_104400_version_view cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200423_104400_version_view cannot be reverted.\n";

		return false;
	}
	*/
}