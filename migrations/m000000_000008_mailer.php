<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2021
 * 2021.04.16., 10:02:42
 * The used disentanglement, and any part of the code
 * m000000_000008_mailer.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m000000_000008_mailer extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable('sendmail', [
			'id' => $this->primaryKey(),
			'priority' => $this->integer(11)->defaultValue(3),
			'sender' => $this->string(255)->defaultValue('System'),
			'to' => $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext')->notNull(),
			'subject' => $this->string(255)->defaultValue(null),
			'body' => $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext')->notNull(),
			'status' => $this->string(255)->defaultValue(null),
			'completed_time' => $this->dateTime()->defaultValue(null),
			'attachment' => $this->text()->defaultValue(null),
			'response' => $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext')->defaultValue(null),
			'timestamp' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
		], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci ENGINE=InnoDB');
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropTable('sendmail');
	}
}