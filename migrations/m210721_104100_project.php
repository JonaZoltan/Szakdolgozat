<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.07.21., 10:41:42
 * The used disentanglement, and any part of the code
 * m210721_104100_project.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m210721_104100_project extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('project', 'partner_ids', $this->text());

		$this->addColumn('partners', 'alert_day', $this->integer()->defaultValue(30));

		$this->addColumn('partners', 'user_ids', $this->text()->comment('Felelősök'));

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropColumn('project', 'partner_ids');
		$this->dropColumn('partners', 'alert_day');
		$this->dropColumn('partners', 'user_ids');
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m210721_104100_project cannot be reverted.\n";

		return false;
	}
	*/
}