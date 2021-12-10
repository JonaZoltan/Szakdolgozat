<?php

use yii\db\Migration;

/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.05.04., 15:07:46
 * The used disentanglement, and any part of the code
 * m20210504_150600_form_schemes.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'time-m-trix' project.

class m210504_150600_form_schemes extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this -> createTable('form_schemes', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255),
			'text' => $this->text(),
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('form_schemes');
	}
}