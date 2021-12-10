<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.07.22., 13:44:42
 * The used disentanglement, and any part of the code
 * m210722_134600_contact_event.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m210722_134600_contact_event extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('contact_event',[
			'id' => $this->primaryKey(),
			'user_id' => $this->integer()->defaultValue(null),
			'partner_id' => $this->integer()->notNull(),
			'contact_id' => $this->integer()->notNull(),
			'type' => $this->integer()->notNull(),
			'when' => $this->dateTime()->notNull(),
			'note' => $this->text()
		], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci ENGINE=InnoDB');

		$this->addForeignKey('ce_uid_u_id_fnk', 'contact_event', 'user_id', 'user', 'id', 'SET NULL');
		$this->addForeignKey('ce_pid_p_id_fnk', 'contact_event', 'partner_id', 'partners', 'id', 'CASCADE');
		$this->addForeignKey('ce_cid_c_id_fnk', 'contact_event', 'contact_id', 'contact', 'id', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('contact_event');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m210722_134600_contact_event cannot be reverted.\n";

		return false;
	}
	*/
}