<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.10.14., 08:51:42
 * The used disentanglement, and any part of the code
 * m201014_085100_holiday_extra.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m201014_085100_holiday_extra extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('holiday_extra', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(11)->notNull(),
			'holiday_day' => $this->integer(11)->notNull(),
			'year' => $this->integer(11)->notNull(),
		]);

		$this->addForeignKey('he_u_id_u_fnk', 'holiday_extra', 'user_id', 'user', 'id');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('holiday_extra');
	}
}