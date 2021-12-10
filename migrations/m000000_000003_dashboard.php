<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.02.29., 17:32:42
 * The used disentanglement, and any part of the code
 * m200229_173200_dashboard.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m000000_000003_dashboard extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->addColumn('user', 'homepage_mode', $this->string(50)->defaultValue('normal')->notNull()->after('rfid'));

		$this->createTable(
			'dashboard', [
				'id' => $this->primaryKey(),
				'dashboard_name' => $this->string(200)->notNull()->defaultValue('Alapértelmezett dashboard'),
				'user_id' => $this->integer(11)->notNull(),
				'current' => $this->tinyInteger(1)->notNull()->defaultValue(1),
			]
		);

		$this->createTable(
			'dashboard_grid', [
				'id' => $this->primaryKey(),
				'name' => $this->string(300)->notNull()->defaultValue(''),
				'dashboard_id' => $this->integer(11)->notNull(),
				'x' => $this->integer(11)->notNull(),
				'y' => $this->integer(11)->notNull(),
				'width' => $this->integer(11)->notNull(),
				'height' => $this->integer(11)->notNull(),
				'type' => $this->string(50)->notNull()->defaultValue('note'),
				'saved_report_id' => $this->integer(11)->notNull()->defaultValue(null),
				'report_view_mode' => $this->string(50)->notNull()->defaultValue(null),
				'note' => $this->text(),
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m200229_173200_dashboard cannot be reverted.\n";
		return false;
	}

	/*
	// Use up()/down() to run migration code without a transaction.
	public function up()
	{

	}

	public function down()
	{
		echo "m200229_173200_dashboard cannot be reverted.\n";

		return false;
	}
	*/
}