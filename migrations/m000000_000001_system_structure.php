<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.03.09., 09:39:42
 * The used disentanglement, and any part of the code
 * m200309_093900_system_structure.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m000000_000001_system_structure extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		// SYSTEM - USER
		$this->createTable(
			'user', [
				'id' => $this->primaryKey(),
				'name' => $this->string(100)->notNull()->defaultValue(''),
				'email' => $this->string(200)->notNull()->defaultValue(null),
				'created_at' => $this->dateTime()->notNull(),
				'password_hash' => $this->string(200)->defaultValue(null),
				'permission_set_id' => $this->integer(11)->defaultValue(null),
				'is_admin' => $this->tinyInteger(1)->defaultValue(0),
				'suspended' => $this->tinyInteger(1)->defaultValue(0),
				'created_by' => $this->integer(11)->defaultValue(null),
				'rfid' => $this->string(30)->defaultValue(null),
				'quickmenu' => $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext')->defaultValue(null),
				'plant_access' => $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext')->defaultValue(null),
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - PERMISSION_SET
		$this->createTable(
			'permission_set', [
				'id' => $this->primaryKey(),
				'name' => $this->string(20)->notNull(),
				'user_id' => $this->integer(11)->notNull()->defaultValue(null),
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - LOG
		$this->createTable(
			'log', [
				'id' => $this->primaryKey(),
				'log_event_id' => $this->integer(11)->defaultValue(null),
				'parameters' => $this->text()->defaultValue(null),
				'user_id' => $this->integer(11)->defaultValue(null),
				'created_at' => $this->dateTime()->defaultValue(null),
				'cached_text' => $this->string(1500)->notNull()->defaultValue(''),
				'timestamp' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - LOG_EVENT
		$this->createTable(
			'log_event', [
				'id' => $this->primaryKey(),
				'name' => $this->string(30)->notNull()->unique(),
				'description' => $this->string(1024)->notNull(),
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - LOGIN
		$this->createTable(
			'login', [
				'id' => $this->primaryKey(),
				'user_id' => $this->integer(11)->notNull(),
				'start_date' => $this->dateTime()->notNull(),
				'end_date' => $this->dateTime()->notNull(),
				'token' => $this->char(40)->notNull(),
				'ip_address' => $this->string(50)->notNull()->defaultValue(''),
				'user_agent' => $this->string(1000)->notNull()->defaultValue(''),
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - VERSION_HISTORY
		$this->createTable(
			'version_history', [
				'id' => $this->primaryKey(),
				'number' => $this->string(45)->notNull(),
				'description' => $this->text()->notNull(),
				'release_date' => $this->date()->notNull(),
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - VERSION_VIEW
		$this->createTable(
			'version_view', [
				'user_id' => $this->integer(11)->notNull(),
				'version_id' => $this->integer(11)->notNull(),
				'when' => $this->dateTime()->notNull(),
				'PRIMARY KEY(user_id)',
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - ERROR_REPORTING
		$this->createTable(
			'error_reporting', [
				'id' => $this->primaryKey(),
				'user_id' => $this->integer(11),
				'message' => $this->text(),
				'user_agent' => $this->string(2048),
				'created_at' => $this->dateTime(),
				'subject' => $this->integer(11),
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - ERROR_REPORTING_SUBJECT
		$this->createTable(
			'error_reporting_subject', [
				'id' => $this->primaryKey(),
				'name' => $this->string(150),
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - CAPABILITY
		$this->createTable(
			'capability', [
				'id' => $this->primaryKey(),
				'name' => $this->string(80)->defaultValue(null),
				'description' => $this->string(1024)->notNull()->defaultValue(''),
				'module' => "ENUM('base', 'plant', 'oee', 'spoi', 'qa', 's5', 'administration', 'otif', 'security', 'monitoring')",
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - PERMISSION
		$this->createTable(
			'permission', [
				'permission_set_id' => $this->integer(11)->notNull(),
				'capability_id' => $this->integer(11)->notNull(),
				'created_at' => $this->dateTime()->notNull(),
				'PRIMARY KEY(permission_set_id, capability_id)',
			], 'ENGINE=InnoDB'
		);

		// SYSTEM - SETTINGS
		$this->createTable(
			'settings', [
				'id' => $this->integer(11)->notNull(),
				'name' => $this->string(100)->notNull()->defaultValue(''),
				'value' => $this->string(2000)->notNull()->defaultValue(''),
			], 'ENGINE=InnoDB'
		);

		// FOREIGN KEY
		//$this->addForeignKey('u_p_set_id_fk', 'user', 'id', 'permission_set', 'permission_set_id');
		$this->addForeignKey('er_u_id_fnk', 'error_reporting', 'user_id', 'user', 'id');
		$this->addForeignKey('er_subj_id_fnk', 'error_reporting', 'subject', 'error_reporting_subject', 'id');
		$this->addForeignKey('u_id_fk', 'version_view', 'user_id', 'user', 'id');
		$this->addForeignKey('v_h_id_fk', 'version_view', 'version_id', 'version_history', 'id');
		$this->addForeignKey('cap_id_idx', 'permission', 'capability_id', 'capability', 'id');

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m000000_100000_system_structure cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m000000_100000_system_structure cannot be reverted.\n";

		return false;
	}
	*/
}