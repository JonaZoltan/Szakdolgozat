<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.03.09., 10:47:42
 * The used disentanglement, and any part of the code
 * m200309_104500_system_data.php own by the author, Bencsik Matyas.
 */

use yii\db\Migration;
use yii\db\Schema;


class m000000_000002_system_data extends Migration
{
	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->batchInsert('capability', ['name', 'description', 'module'], [
			['users', 'Felhasználók megtekintése és szerkesztése', 'base'],
			['logins', 'Munkamenetek megtekintése és törlése', 'base'],
			['permissiongroups', 'Jogosultság csoportok szerkesztése', 'base'],
			['permissions', 'Jogosultságok szerkesztése', 'base'],
			['import', 'Adatok importálása', 'base'],
			['export', 'Adatok exportálása', 'base'],
			['logs', 'Naplóbejegyzések megtekintése.', 'base'],
			['backup', 'Biztonsági mentés készítés.', 'base'],
			['groupsettings', 'Csoport beállítások', 'base'],
		]);

		$this->batchInsert('log_event', ['name', 'description'], [
			['felhasznalo.bejelentkezes', '{nev} belépett a szoftverbe.'],
			['felhasznalo.kijelentkezes', '{nev} ({email}) kijelentkezett.'],
			['felhasznalo.uj', '{felhasznalo} hozzáadott egy új felhasználót a rendszerhez: {email}'],
			['import.use', '{felhasznalo} használta az import funkciót a következö adathalmazra: {adathalmaz}'],
			['export.use', '{felhasznalo} használta az export funkciót a következö adathalmazra: {adathalmaz}'],
		]);


		$this->batchInsert('settings', ['id', 'name', 'value'], [
			[1, 'smtp_name', 'support@szitar.hu'],
			[2, 'smtp_address', 'support@szitar.hu'],
			[3, 'smtp_host', 'smtp.office365.com'],
			[4, 'smtp_port', '587'],
			[5, 'smtp_security', 'tls'],
			[6, 'smtp_username', 'support@szitar.hu'],
			[7, 'smtp_password', '123Szitaronline'],
		]);

		$this->batchInsert('error_reporting_subject', ['id', 'name'], [
			[1, 'Általános'],
			[2, 'Rendszerhiba'],
			[3, 'Szolgáltatás kimaradás'],
		]);

		$this->batchInsert('permission_set', ['id', 'name', 'user_id'], [
			[1, 'Céges Admin', null],
		]);

		$this->batchInsert('user', ['id', 'name', 'email', 'created_at', 'password_hash', 'permission_set_id', 'is_admin', 'suspended', 'created_by', 'rfid', 'quickmenu'], [
			[1, 'Bencsik Mátyás', 'matyas.bencsik@gmail.com', '2020-01-01 12:00:00', '$2y$10$E904QF02ur4WiNzFRnT.6ucVzr/Nn5hTWeJ9exU6C2UIBSMlJaAl.', NULL, 1, 0, NULL, NULL, NULL],
			[2, 'Márki Gábor', 'mgabor411@gmail.com', '2020-01-01 12:00:00', '$2y$10$m0.x8i0Zqwy4tCkJ9hKLdOfAzlmDh/HKtipkyWyisybfCwEPaAgju', NULL, 1, 0, NULL, NULL, NULL],
			[3, 'Kabai Márton', 'marton.kabai@szitar.hu', '2020-01-01 12:00:00', '$2y$10$7/mQJSs4LRCDsox01EJVK.J3cSQSahoCuUdncXe.F2BGe18pNuaCC', NULL, 1, 0, NULL, NULL, NULL],
			[4, 'Jóna Zoltán', 'zoltan.jona@szitar.hu', '2020-01-01 12:00:00', '$2y$10$TbJ8YaDxg5yi19Pxi7qwSOHvsdFlrKLJ0.zugk4CylI/neqyqyF2q', NULL, 1, 0, NULL, NULL, NULL],
		]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		echo "m000000_200000_system_data cannot be reverted.\n";
		return false;
	}

}