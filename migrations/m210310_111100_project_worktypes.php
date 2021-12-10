<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.03.10., 11:12:46
 * The used disentanglement, and any part of the code
 * m210310_111100_project_worktype.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'time-m-trix' project. 


class m210310_111100_project_worktypes extends \yii\db\Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this -> createTable('project_worktypes', [
			'id' => $this->primaryKey(),
			'project_id' => $this->integer(11),
			'worktype_id' => $this->integer(11)
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('project_worktypes');
	}
}