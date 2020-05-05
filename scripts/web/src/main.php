<?php
session_start();

if (! isset($_SESSION["uid"])) {
	header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/login.php"); exit();
}

require("db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if ($_POST["logout"]) { 
		session_unset();
		session_destroy();
		header("Location: login.php");
		exit();
	}
	else if (isset($_POST["delete_note_id"])) { 
		$note_id = $_POST["delete_note_id"];
		delete_note_with_id((int)$note_id, $_SESSION["uid"]);
		header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/main.php");
		exit();
	}
	else if (isset($_POST['search_keywords'])) {
		$keywords = tokenize($_POST['search_keywords']);
		$decrypt_key = isset($_POST['decrypt_key'])? $_POST['decrypt_key']:"";
		$uid = $_SESSION['uid'];
		$documents = search_keywords($keywords, $decrypt_key, $uid);
		$documents = remove_duplicate_documents($documents);
		$_SESSION['search_result'] = $documents;
	}
}

function tokenize($str) {
	/* Tokenize and make it lowercase for comparison */
	$remove_char = ".\/,?!\t\n";
	$n = strlen($remove_char);
	for ($i = 0; $i < $n; $i++) {
		$str = str_replace($remove_char[$i], '', $str);
	}
	$lowercase_str = strtolower($str);
	return explode(" ", $lowercase_str);
}

function remove_duplicate_documents($documents) {
	$repeat_free_documents = array();
	$__id = array();
	foreach ($documents as $d) {
		$id = $d[0];
		if (! in_array($id, $__id)) {
			array_push($__id, $id);
			array_push($repeat_free_documents, $d);
		}
	}
	return $repeat_free_documents;
}

?>

<!DOCTYPE>
<html>
	<head>
		<title> My Note </title>
	</head>
	<body>
		<div>
			<form method="post">
				<input type="submit" name="logout" class="button" value="logout">
			</form>
		</div>
		<div>
			<center>
				Welcome Back,  <?php echo getUsernameByID($_SESSION["uid"]);?>
			</center>
		<div>
			<tr>
				<td><a href="create_note.php">Create Note</a></td>
				<td>
					<center>
						<form action="" method="post">
							<table>
								<tr>
									<td>search:</td>
									<td><input type="text" name="search_keywords"></td>
								</tr>
								<tr>
									<td>pass: </td>
									<td><input type="text" name="decrypt_key"></td>
								</tr>
								<tr>
									<td><input type="submit" name="submit" value="search"><td>
								</tr>
							</table>
						</form>
					</center>
				</td>
			</tr>	
		</div>
		<div>
			<?php
				echo "<left>";
				if (! isset($_SESSION['search_result'])) {
					$result = list_notes($_SESSION["uid"]);
					if (!$result) {
						echo $NULL;
					}
					else {
						foreach ($result as $r) {				
							$isencrypt = $r[3] == 1? "encrypted": "not encrypted";
							echo "<pre>", "<a href=view.php?", "note_id=$r[0]>", $r[1], "</a>", "\t\t", $r[2], "\t", $isencrypt, "\t\t"
							,"<form method='post'><button name='delete_note_id' type='submit' value=$r[0]>delete</button></form>",
							"</pre>";
							echo "<br>";
						}
					}
				} else {
					echo "<h3>Search result: </h3>";
					echo "<a href='main.php'>Back to main</a><br><br>";
					foreach ($_SESSION['search_result'] as $r) {
						$isencrypt = $r[3] == 1? "encrypted": "not encrypted";
						echo "<pre>", "<a href=view.php?", "note_id=$r[0]>", $r[1], "</a>", "\t\t", $r[2], "\t", $isencrypt, "\t\t"
						,"<form method='post'><button name='delete_note_id' type='submit' value=$r[0]>delete</button></form>",
						"</pre>";
						echo "<br>";
					}
					unset($_SESSION['search_result']);
				}
				echo "</left>";
			?>
		</div>
	</body>
</html>
