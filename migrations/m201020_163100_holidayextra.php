<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.10.20., 16:31:42
 * The used disentanglement, and any part of the code
 * m201020_163100_holidayextra.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m201020_163100_holidayextra extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('holiday_extra', 'disabled_user', $this->text()->defaultValue(null)->after('user_id'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('holiday_extra', 'disabled_user');
	}
}