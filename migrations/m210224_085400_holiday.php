<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.02.24., 08:54:46
 * The used disentanglement, and any part of the code
 * m210224_0854_holiday.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'time-m-trix' project. 


class m210224_085400_holiday extends \yii\db\Migration
{

	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('holiday', 'email_sent', $this->tinyInteger()->defaultValue(0)->after('description'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('holiday', 'email_sent');
	}

}