<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.08.31., 19:01:42
 * The used disentanglement, and any part of the code
 * m200831_190000_area.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m200831_190000_area extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('workplace', 'default', $this->boolean()->defaultValue(false));
		$this->addColumn('project_membership', 'leader', $this->boolean()->defaultValue(false)->after('project_id'));
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('workplace', 'default');
		$this->dropColumn('project_membership', 'leader');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200831_190000_area cannot be reverted.\n";

		return false;
	}
	*/
}