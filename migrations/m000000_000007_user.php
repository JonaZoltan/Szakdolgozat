<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.06.16., 08:58:42
 * The used disentanglement, and any part of the code
 * m000000_000007_user.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m000000_000007_user extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->dropColumn('user', 'plant_access');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m000000_000007_user cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m000000_000007_user cannot be reverted.\n";

		return false;
	}
	*/
}