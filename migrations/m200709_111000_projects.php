<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.07.09., 11:10:42
 * The used disentanglement, and any part of the code
 * m20200709_111000_project.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m200709_111000_projects extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('worktype', [
			'id' => $this->primaryKey(),
			'title' => $this->string(50)->defaultValue(null),
		]);

		$this->createTable('workplace', [
			'id' => $this->primaryKey(),
			'title' => $this->string(50)->defaultValue(null),
		]);

		$this->createTable('area', [
			'id' => $this->primaryKey(),
			'title' => $this->string(50)->defaultValue(null),
		]);


		$this->createTable('project', [
			'id' => $this->primaryKey(),
			'area_id' => $this->integer(11)->notNull(),
			'name' => $this->string(255)->notNull(),
			'text' => $this->text()->defaultValue(null),
			'color' => $this->string(255)->notNull(),
			'timestamp' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),

		]);
		$this->addForeignKey('a_aid_p_aeid_fnk', 'project', 'area_id', 'area', 'id', 'SET NULL');

		$this->createTable('project_membership', [
			'id' => $this->primaryKey(),
			'user_id' => $this->integer(11)->notNull(),
			'project_id' => $this->integer(11)->notNull(),
			'level' => $this->integer(11)->notNull(),
			'financing' => $this->integer(11)->notNull(),
			'member_since' => $this->dateTime()->notNull(),
		]);

		$this->addForeignKey('m_uid_u_id_fnk', 'project_membership', 'user_id', 'user', 'id', 'CASCADE');
		$this->addForeignKey('m_pid_p_id_fnk', 'project_membership', 'project_id', 'project', 'id', 'CASCADE');


		$this->createTable('tasks', [
			'id' => $this->primaryKey(),
			'project_id' => $this->integer(11)->notNull(),
			'worktype_id' => $this->integer(11)->notNull(),
			'workplace_id' => $this->integer(11)->notNull(),
			'user_id' => $this->integer(11)->notNull(),
			'text' => $this->text()->defaultValue(null),
			'working_datetime_start' => $this->dateTime()->notNull(),
			'working_datetime_end' => $this->dateTime()->notNull(),
			'recommended_hours' => $this->integer(11)->notNull(),
			'recognized' => $this->boolean()->defaultValue(false),
		]);

		$this->addForeignKey('p_pid_p_id_fnk', 'tasks', 'project_id', 'project', 'id', 'CASCADE');
		$this->addForeignKey('p_wid_wt_id_fnk', 'tasks', 'worktype_id', 'worktype', 'id', 'SET NULL');
		$this->addForeignKey('p_wid_wp_id_fnk', 'tasks', 'workplace_id', 'workplace', 'id', 'SET NULL');
		$this->addForeignKey('p_uid_u_id_fnk', 'tasks', 'user_id', 'user', 'id', 'SET NULL');
	}


	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('project_membership');
		$this->dropTable('project');


		$this->dropTable('tasks');

		$this->dropTable('worktype');
		$this->dropTable('workplace');
		$this->dropTable('area');

	}
}