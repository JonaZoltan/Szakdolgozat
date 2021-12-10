<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.10.12., 15:00:42
 * The used disentanglement, and any part of the code
 * m101200_150000_holiday_capability.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m101200_150000_holiday_capability extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->insert('capability', ['name' => 'holiday', 'description' => 'Szabadságkérelmeket módosíthat.', 'module' => 'base']);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->delete('capability', ['name' => 'holiday']);
	}
}