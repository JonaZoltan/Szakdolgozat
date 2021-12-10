<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.07.28., 11:59:42
 * The used disentanglement, and any part of the code
 * m210728_120000_cap.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m210728_120000_cap extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->batchInsert('capability', ['name', 'description', 'module'], [
			['view_partners', 'Láthatja: partners', 'base'],
			['create_partners', 'Létre tudja hozni: partners', 'base'],
			['update_partners', 'Módosíthatja: partners', 'base'],
			['delete_partners', 'Törölheti: partners', 'base'],

			['view_contact_event', 'Láthatja: contact_event', 'base'],
			['create_contact_event', 'Létre tudja hozni: contact_event', 'base'],
			['update_contact_event', 'Módosíthatja: contact_event', 'base'],
			['delete_contact_event', 'Törölheti: contact_event', 'base'],
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m210728_120000_cap cannot be reverted.\n";
		return false;
	}
}