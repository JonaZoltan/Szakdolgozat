<?php
/**
 * This file made with PhpStorm by the author.
 * @author Bencsik Matyas
 * @email matyas.bencsik@gmail.com, matyas.bencsik@besoft.hu
 * @copyright 2020
 * 2020.01.31., 13:03:46
 * The used disentanglement, and any part of the code
 * rfid.php own by the author, Bencsik Matyas.
 */

//error_reporting - E_ERROR | E_WARNING | E_PARSE | E_ALL
ini_set("display_errors", 0);
ini_set('error_reporting', E_ALL);


if(isset($_GET['mac'], $_GET['id'])) {

	$hostData = [
		"host" => "localhost",
		"user" => "root",
		"password" => "",
		"db" => "c1_matrix01"
	];

	$conn = new mysqli($hostData['host'], $hostData['user'], $hostData['password'], $hostData['db']);

	$error = false;
	$open = 0;

	$sql = "SELECT * FROM user WHERE `rfid`='".$_GET['id']."'";
	$result = $conn->query($sql);
	$result = $result->fetch_assoc();
	if (!$result) {
		$error = true;
	} else { // Található user.
		// Aktuális dátum
		$date = date('Y-m-d');
		$dateTime = date('Y-m-d H:i:s');

		// Tényleges mozgás rögzítése
		$sql = "SELECT * FROM worktimes WHERE user_id = {$result['id']} AND date(stepin) = '{$date}' AND stepout IS NULL";
		$worktimes = $conn->query($sql);
		$worktimes = $worktimes->fetch_assoc();

		if(!$worktimes) {
			$sql = "INSERT INTO worktimes(user_id, stepin) VALUES ({$result['id']}, '{$dateTime}')";
			$open = 1;
			$conn->query($sql);
		} else {
			$sql = "UPDATE worktimes SET stepout = '{$dateTime}' WHERE id = {$worktimes['id']}";
			$conn->query($sql);
		}
		// Tényleges mozgás rögzítése end

		// Real / Jelenléti rögzítése
		$sql = "SELECT * FROM worktimes_real WHERE user_id = {$result['id']} AND date(stepin) = '{$date}'";
		$wReal = $conn->query($sql)->fetch_assoc();

		if(!$wReal) {
			$sql = "INSERT INTO worktimes_real(user_id, stepin) VALUES ({$result['id']}, '{$dateTime}')";
			$conn->query($sql);
		} else {
			// Ha belépés akkor újra null
			if(!$worktimes)
				$sql = "UPDATE worktimes_real SET stepout = NULL WHERE id = {$wReal['id']}";
			else // Ha kilépés akkor időpont
				$sql = "UPDATE worktimes_real SET stepout = '{$dateTime}' WHERE id = {$wReal['id']}";

			$conn->query($sql);
		}
		// Real / Jelenléti rögzítése end
	}

	$sql = "INSERT INTO rfid_log(`from`, `rfid`, `open`) VALUES ('{$_GET['mac']}', '{$_GET['id']}', {$open});";
	$conn->query($sql);

	if ($error) {
		echo "<buzz>2</buzz>";
		echo "<ledr>1,1,40</ledr>";
	} else {
		//echo "<buzz>1</buzz>";
		echo "<ledg>40,0,1</ledg>";
		if($open)
			echo "<open>1</open>";
	}

	$conn->close();
}

?>