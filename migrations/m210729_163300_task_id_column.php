<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.07.29., 16:32:42
 * The used disentanglement, and any part of the code
 * m210729_163300_add_task_id_column.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m210729_163300_task_id_column extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('contact_event', 'task_id', $this->integer()->defaultValue(null));
		$this->addForeignKey('ce_tid_t_id_fnk', 'contact_event', 'task_id', 'tasks', 'id', 'CASCADE');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropForeignKey('ce_tid_t_id_fnk', 'contact_event');
		$this->dropColumn('contact_event', 'task_id');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m210729_163300_add_task_id_column cannot be reverted.\n";

		return false;
	}
	*/
}