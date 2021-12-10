<?php

namespace app\controllers;

use app\modules\users\models\User;

trait UserCan {

	/**
	 * @return mixed
	 */
	private function getCapabilities() {
		return (new User())->getCapabilities($this->userData['permission_set_id']);
	}

	/**
	 * @param $capability_name
	 * @return bool
	 */
	public function userCan($capability_name) {
		if(!isset($this->userData)) {
			return false; // Ha nincs "login" / "adat"
		}

		if ($this->userData['is_admin']) {
			return true; // az adminnak bármihez van  jogosultsága
		}

		if (!$this->userData['permission_set_id']) {
			return false; // Nincs semmihez joga az illetőnek.
		}

		if (!in_array($capability_name, $this->capabilities)) {
			return false; // Nem található. Ilyenkor biztonsági okokból ne legyen jogosultsága hozzá az illetőnek.
		}

		if(!empty($this->capabilities)) {
			foreach ($this->capabilities as $name) {
				if ($name == $capability_name) {
					return true; // található
				}
			}
		}

		return false;
	}

	/**
	 * Amennyiben minden tulajdonsag birtikaban van true
	 * @param $capability_names
	 * @return bool
	 */
	public function userCanAll($capability_names) {
		foreach ($capability_names as $capability_name) {
			if (!$this->userCan($capability_name)) {
				return false;
			}
		}
		return true;
	}


	/**
	 * Amennyiben egy tulajdonsag birtokaban van true.
	 * @param $capability_names
	 * @return bool
	 */
	public function userCanOne($capability_names) {
		foreach ($capability_names as $capability_name) {
			if ($this->userCan($capability_name)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param $capability_name
	 */
	public function userCanRedirect($capability_name) {
		if(!$this->userCan($capability_name))
			$this->redirect('/');
	}

	/**
	 * @param $capability_name
	 */
	public function userCanOneRedirect($capability_name) {
		if(!$this->userCanOne($capability_name))
			$this->redirect('/');
	}

	/**
	 * @param $capability_name
	 */
	public function userCanAllRedirect($capability_name) {
		if(!$this->userCanAll($capability_name))
			$this->redirect('/');
	}
}
