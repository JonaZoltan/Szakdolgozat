<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.08.31., 20:15:42
 * The used disentanglement, and any part of the code
 * m200831_200000_capability.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m200831_200000_capability extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->batchInsert('capability', ['name', 'description', 'module'], [
			['view_tasks', 'Láthatja: tasks', 'base'],
			['create_tasks', 'Létre tudja hozni: tasks', 'base'],
			['update_tasks', 'Módosíthatja: tasks', 'base'],
			['delete_tasks', 'Törölheti: tasks', 'base'],

			['view_project', 'Láthatja: project', 'base'],
			['create_project', 'Létre tudja hozni: project', 'base'],
			['update_project', 'Módosíthatja: project', 'base'],
			['delete_project', 'Törölheti: project', 'base'],

			['view_area', 'Láthatja: area', 'base'],
			['create_area', 'Létre tudja hozni: area', 'base'],
			['update_area', 'Módosíthatja: area', 'base'],
			['delete_area', 'Törölheti: area', 'base'],

			['view_project_membership', 'Láthatja: project_membership', 'base'],
			['create_project_membership', 'Létre tudja hozni: project_membership', 'base'],
			['update_project_membership', 'Módosíthatja: project_membership', 'base'],
			['delete_project_membership', 'Törölheti: project_membership', 'base'],

			['view_worktype', 'Láthatja: worktype', 'base'],
			['create_worktype', 'Létre tudja hozni: worktype', 'base'],
			['update_worktype', 'Módosíthatja: worktype', 'base'],
			['delete_worktype', 'Törölheti: worktype', 'base'],

			['view_workplace', 'Láthatja: workplace', 'base'],
			['create_workplace', 'Létre tudja hozni: workplace', 'base'],
			['update_workplace', 'Módosíthatja: workplace', 'base'],
			['delete_workplace', 'Törölheti: workplace', 'base'],
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m200831_200000_capability cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200831_200000_capability cannot be reverted.\n";

		return false;
	}
	*/
}