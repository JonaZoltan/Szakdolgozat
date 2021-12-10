<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.10.07., 14:42:42
 * The used disentanglement, and any part of the code
 * m000000_000007_error_reporting.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m000000_000007_error_reporting extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('error_reporting_message', [
			'id' => $this->primaryKey(),
			'error_reporting_id' => $this->integer(11)->notNull(),
			'user_id' => $this->integer(11)->notNull(),
			'text' => $this->text()->defaultValue(null),
			'timestamp' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
		]);

		$this->addForeignKey('erm_u_id_u_fnk', 'error_reporting_message', 'user_id', 'user', 'id');
		$this->addForeignKey('erm_er_id_er_fnk', 'error_reporting_message', 'error_reporting_id', 'error_reporting', 'id', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('error_reporting_message');
	}
}