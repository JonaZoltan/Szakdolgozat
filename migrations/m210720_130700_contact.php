<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.07.20., 13:06:42
 * The used disentanglement, and any part of the code
 * m210720_130700_contact.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m210720_130700_contact extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('contact',[
			'id' => $this->primaryKey(),
			'partner_id' => $this->integer()->notNull(),
			'name' => $this->string(250),
			'email' => $this->string(250),
			'tel' => $this->string(250),
			'position' => $this->string(250),
			'note' => $this->text()
		], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci ENGINE=InnoDB');

		$this->addForeignKey('partner_contact', 'contact', 'partner_id', 'partners', 'id', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('partner_contact', 'contact');
		$this->dropTable('contact');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m210720_130700_contact cannot be reverted.\n";

		return false;
	}
	*/
}