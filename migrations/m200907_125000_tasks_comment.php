<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.09.07., 12:50:42
 * The used disentanglement, and any part of the code
 * m200907_125000_tasks_comment.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m200907_125000_tasks_comment extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('tasks', 'comment', $this->string(255)->defaultValue(null)->after('recognized'));
		$this->dropColumn('project_membership', 'level');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('tasks', 'comment');
		$this->addColumn('project_membership', 'level', $this->integer(11)->defaultValue(1)->after('leader'));
	}
}