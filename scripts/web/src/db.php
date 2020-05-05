<?php
	
/*	This file is entirely for database connection and 
 *	sql query. Every file which need database connection will
 *	add require('db.php') in the corresponding script */

define("HOST", "mysql");
define("USER", "web_developer");
define("PASS", "P@ssw0rd");
define("DB", "development");
	
$conn = new mysqli(HOST, USER, PASS, DB);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

function getUsernameByID($uid) {
	global $conn;
	$stmt = $conn->prepare(
		"SELECT username FROM accounts WHERE id = ?");
	$stmt->bind_param("s", $uid);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows === 0) { die("Uid not found"); }
	$stmt->bind_result($value);
	$stmt->fetch();
	return $value;
}

function login_validation($username, $plain_text_password) {
	/* given username, password (in plain text), output. 
		Should be used in login page */
	global $conn;

	$stmt = $conn->prepare(
		"SELECT id FROM accounts WHERE username = ? and pass = 
		(SELECT SHA2((SELECT CONCAT(?, (SELECT salt FROM accounts WHERE username = ?))), 256));");
	$stmt->bind_param("sss", $username, $plain_text_password, $username);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows > 1) die("Wired! Should not be more than one record.");
	else if ($stmt->num_rows === 1) {
		$stmt->bind_result($id);
		$stmt->fetch();
		return $id;
	}
	else die("invalid login credential");
}

function salt_generation() {
	/*
	$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()\/{}";
	$salt_length = 10;
	$chars_length = strlen($chars);
	$salt = "";
	for ($i = 0; $i < $salt_length; $i++) {
		$pos = random_int(0, $chars_length);
		$salt .= $chars[$pos];
	}
	*/
	global $conn;
	$result = $conn->query("SELECT RANDOM_BYTES(16)");
	$salt = $result->fetch_assoc()['RANDOM_BYTES(16)'];
	return $salt;
}

function password_hashed($plain_text_password, $salt) {
	global $conn;
	$pass_with_salt = $plain_text_password . $salt;
	$stmt = $conn->prepare("SELECT SHA2(?, 256)");
	$stmt->bind_param("s", $pass_with_salt);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows === 0) die("No result from password_hashed");
	$stmt->bind_result($value);
	$stmt->fetch();
	return $value;
}

function username_already_in_use($username) {
	global $conn;
	$stmt = $conn->prepare(
		"SELECT username FROM accounts WHERE username = ?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows === 0) return False;
	return True;
}

function password_strength($plain_text_password) {
	global $conn;
	$stmt = $conn->prepare("SELECT VALIDATE_PASSWORD_STRENGTH(?)");
	$stmt->bind_param("s", $plain_text_password);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows === 0) die("Input plain_text_password is problematic");
	$stmt->bind_result($value);
	$stmt->fetch();
	return (int)$value;
}

function create_user
($first_name, $last_name, $username, $plain_text_password) {
	/* Create user, return user id for the session
		will be used in the Register page */
	global $conn;

	if (username_already_in_use($username)) {
		die("user name $username already in use");
	}
	if (password_strength($plain_text_password) < 50) {
		$v = password_strength($plain_text_password);
		die("can you use a better password? Your password $plain_text_password is a weak password ($v), try to include some special characters");
	}	

	$salt = salt_generation();
	$secure_password = password_hashed($plain_text_password, $salt);
	$stmt = $conn->prepare(
		"INSERT INTO accounts (first_name, last_name, username, pass, salt)
			VALUES (?, ?, ?, ?, ?)");
	$stmt->bind_param(
		"sssss", $first_name, $last_name, $username, $secure_password, $salt);
	$stmt->execute();
	return $stmt->insert_id;
}

function create_note
($title, $content, $isencrypt, $encrypt_pass, $creation_date, $uid) {
	/* Create note, return */
	global $conn;
	$stmt = NULL;

	if (! $isencrypt) {
		$stmt = $conn->prepare(
			"INSERT INTO notes 
			(title, content, isencrypt, creation_date, uid, initial_vector)
				VALUES (?, ?, ?, ?, ?, NULL)");
		$stmt->bind_param(
			"ssisi", $title, $content, $isencrypt, $creation_date, $uid);
	} else {

		$result = $conn->query("SELECT RANDOM_BYTES(16)");
		$iv = $result->fetch_assoc()['RANDOM_BYTES(16)'];
		$stmt = $conn->prepare(
			"INSERT INTO notes (title, content, isencrypt, creation_date, uid, initial_vector)
				VALUES (?, TO_BASE64(AES_ENCRYPT(?, SHA2(?,512), ?)), ?, ?, ?, ?)");
		$stmt->bind_param(
			"ssssisis", $title, $content, $encrypt_pass, $iv, $isencrypt, $creation_date, $uid, $iv);
	}
	return $stmt->execute();
	
}

function list_notes($uid) {
	/* return a list with field id, creation_date, title, isencrypt */
	global $conn;
	$stmt = $conn->prepare(
		"SELECT id, title, creation_date, isencrypt FROM notes WHERE uid = ? ORDER BY creation_date DESC");
	$stmt->bind_param("s", $uid);
	$stmt->execute();
	
	$resultSet = $stmt->get_result();
	$result = $resultSet->fetch_all();
	return $result;
}

function delete_note_with_id($note_id, $uid) {
	global $conn;
	$stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND uid = ?");
	$stmt->bind_param("ii", $note_id, $uid);
	$stmt->execute();
}

function validate2viewNote($note_id, $uid) {
	global $conn;
	$stmt = $conn->prepare(
		"SELECT uid FROM notes WHERE id = ?");
	$stmt->bind_param("i", $note_id);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows === 0) die("$note_id Note id not found?");
	$stmt->bind_result($value);
	$stmt->fetch();
	$fetched_uid = (int)$value;
	if ($uid == $fetched_uid) return True;
	return False;
}

function note_retrieve($note_id) {
	global $conn;
	$stmt = $conn->prepare(
		"SELECT title, content, creation_date, isencrypt FROM notes WHERE id = ?");
	$stmt->bind_param("i", $note_id);
	$stmt->execute();
	$resultSet = $stmt->get_result();
	$result = $resultSet->fetch_all();
	return $result;
}

function decrypt_note_message($note_id, $decrypt_key, $uid) {
	global $conn;
	$stmt = $conn->prepare(
		"SELECT AES_DECRYPT(
			FROM_BASE64((SELECT content FROM notes WHERE id = ? AND uid = ?)), 
			SHA2(?, 512), 
			(SELECT initial_vector FROM notes WHERE id = ?))");
	$stmt->bind_param("iisi", $note_id, $uid, $decrypt_key, $note_id);
	$stmt->execute();
	$stmt->store_result();
	if ($stmt->num_rows === 0) die("Wrong decryption key");
	$stmt->bind_result($message);
	$stmt->fetch();
	return $message;
}

function search_keywords($keywords, $decrypt_key, $uid) {
	global $conn;
	$documents = array();
	foreach ($keywords as $k) {
		$stmt = $conn->prepare(
			"SELECT id, title, creation_date, isencrypt FROM notes WHERE uid = ? AND 
			isencrypt = 0 AND content LIKE ? ORDER BY creation_date DESC");
		$keyword = "%" . $k . "%";
		$stmt->bind_param("is", $uid, $keyword);
		$stmt->execute();
		$resultSet = $stmt->get_result();
		$result = $resultSet->fetch_all();
		foreach ($result as $r) {
			array_push($documents, $r);
		}
	}
	if (! $decrypt_key) return $documents;

	/* if decrypt_key is provided */

	/* Retrieve the encrypted document */
	$stmt2 = $conn->prepare(
		"SELECT id, title, creation_date, isencrypt, content, initial_vector 
		FROM notes WHERE uid = ? AND isencrypt = 1 ORDER BY creation_date DESC");
	$stmt2->bind_param("i", $uid);
	$stmt2->execute();
	$resultSet = $stmt2->get_result();
	$result = $resultSet->fetch_all();
	foreach ($result as $r) {
		$note_id = $r[0];
		$title = $r[1];
		$creation_date = $r[2];
		$isencrypt = $r[3];
		$content = $r[4];
		$iv = $r[5];
		
		$stmt3 = $conn->prepare("SELECT AES_DECRYPT(FROM_BASE64(?), SHA2(?, 512), ?)");
		$stmt3->bind_param("sss", $content, $decrypt_key, $iv);
		$stmt3->execute();

		$stmt3->store_result();
		$stmt3->bind_result($message);
		$stmt3->fetch();

		foreach ($keywords as $k) {
			if(strpos(strtolower($message), $k) !== false) {
				/* The message should not be Null */
				/* if the Msg contains the keyword */
				array_push($documents, array_slice($r,0, 4));
			}
		}
		
	}

	return $documents;
}
?>
