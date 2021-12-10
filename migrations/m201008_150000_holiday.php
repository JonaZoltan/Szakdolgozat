<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.10.08., 15:03:42
 * The used disentanglement, and any part of the code
 * m201008_150000_holiday.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m201008_150000_holiday extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('holiday', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(11)->notNull(),
			'date' => $this->date()->notNull(),
			'accepted' => $this->tinyInteger()->defaultValue(0),
			'description' => $this->text()->defaultValue(null),
			'timestamp' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
		]);

		$this->addForeignKey('h_uid_u_fnk', 'holiday', 'user_id', 'user', 'id', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('holiday');
	}
}