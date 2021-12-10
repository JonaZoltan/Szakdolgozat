<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.09.25., 10:33:42
 * The used disentanglement, and any part of the code
 * m200925_103400_worktimes.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m200925_103400_worktimes extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable(
			'rfid_log', [
			'id' => $this->primaryKey(),
			'from' => $this->string(45)->defaultValue(null),
			'rfid' => $this->string(45)->defaultValue(null),
			'open' => $this->boolean()->defaultValue(false),
			'timestamp' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
		], 'ENGINE=InnoDB');

		$this->createTable('worktimes', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(11)->notNull(),
			'stepin' => $this->dateTime()->notNull(),
			'stepout' => $this->dateTime()->defaultValue(null),
			'comment' => $this->text()->defaultValue(null),
		], 'ENGINE=InnoDB');

		$this->addForeignKey('w_uid_u_id_fnk', 'worktimes', 'user_id', 'user', 'id');

		$this->createTable('worktimes_real', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(11)->notNull(),
			'manual' => $this->boolean()->defaultValue(false),
			'stepin' => $this->dateTime()->notNull(),
			'stepout' => $this->dateTime()->defaultValue(null),
			'comment' => $this->text()->defaultValue(null),
		], 'ENGINE=InnoDB');

		$this->addForeignKey('wr_uid_u_id_fnk', 'worktimes_real', 'user_id', 'user', 'id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('rfid_log');
		$this->dropTable('worktimes');
		$this->dropTable('worktimes_real');
	}
}