<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.03.19., 13:45:46
 * The used disentanglement, and any part of the code
 * m210319_134400_archived.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'timematrix' project. 


class m210319_134400_archived extends \yii\db\Migration
{
	public function safeUp()
	{
		$this->addColumn('project', 'archived', $this->boolean()->defaultValue(null)->after('timestamp'));
		$this->addColumn('workplace', 'archived', $this->boolean()->defaultValue(null)->after('default'));
	}

	public function safeDown()
	{
		$this->dropColumn('project', 'archived');
		$this->dropColumn('workplace', 'archived');
	}

}