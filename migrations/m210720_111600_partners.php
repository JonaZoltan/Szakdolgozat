<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.07.20., 11:15:42
 * The used disentanglement, and any part of the code
 * m210720_111600_partners.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m210720_111600_partners extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('partners',[
			'id' => $this->primaryKey(),
			'name' => $this->string(250)->notNull(),
			'note' => $this->text()
		], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci ENGINE=InnoDB');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('partners');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m210720_111600_partners cannot be reverted.\n";

		return false;
	}
	*/
}