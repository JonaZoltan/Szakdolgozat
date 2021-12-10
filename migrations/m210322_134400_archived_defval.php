<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.03.22., 12:13:46
 * The used disentanglement, and any part of the code
 * m210322_134400_archived_defval.php own by the author, Bencsik Matyas.
 */


//@todo Implement in this 'timematrix' project. 


class m210322_134400_archived_defval extends \yii\db\Migration
{
	public function safeUp()
	{
		$this->alterColumn('project', 'archived', $this->boolean()->defaultValue(false)->after('timestamp'));
		$this->alterColumn('workplace', 'archived', $this->boolean()->defaultValue(false)->after('default'));
		$this->update('project', ['archived' => false], ['archived' => null]);
		$this->update('workplace', ['archived' => false], ['archived' => null]);
	}

	public function safeDown()
	{
		$this->alterColumn('project', 'archived', $this->boolean()->defaultValue(null)->after('timestamp'));
		$this->alterColumn('workplace', 'archived', $this->boolean()->defaultValue(null)->after('default'));
		$this->update('project', ['archived' => null], ['archived' => false]);
		$this->update('workplace', ['archived' => null], ['archived' => false]);
	}
}