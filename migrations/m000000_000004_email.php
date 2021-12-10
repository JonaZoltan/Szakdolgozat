<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.04.16., 10:51:42
 * The used disentanglement, and any part of the code
 * m000000_000004_email.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m000000_000004_email extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->update('settings', ['value' => '123Szitaronline'], ['name' => 'smtp_password']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->update('settings', ['value' => '123Szita1'], ['name' => 'smtp_password']);
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m000000_000004_email cannot be reverted.\n";

		return false;
	}
	*/
}