<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.03.04., 13:11:46
 * The used disentanglement, and any part of the code
 * m210304_120000_planned_verify_verifycomment.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'time-m-trix' project. 


class m210304_120000_planned_verify_verifycomment extends \yii\db\Migration
{

	public function safeUp()
	{
		$this->addColumn('tasks', 'planned', $this->boolean()->defaultValue(null)->after('comment'));
		$this->addColumn('tasks', 'verified', $this->boolean()->defaultValue(null)->after('planned'));
		$this->addColumn('tasks', 'verified_comment', $this->text()->defaultValue(null)->after('verified'));
	}

	public function safeDown()
	{
		$this->dropColumn('tasks', 'planned', 'verified', 'verified_comment');
	}
}