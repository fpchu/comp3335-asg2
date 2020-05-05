<?php
session_start();

if (! isset($_SESSION["uid"])) { 
	header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/main.php"); exit(); }

require("db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {	
	$note_title = $_POST["title"];
	$note_content = $_POST["content"];
	$creation_date = date("Y-m-d H:i:s");
	$encrypt_pass = $_POST["encrypt_pass"];
	$isencrypt = ($encrypt_pass? 1:0);
	$uid = $_SESSION["uid"];
	
	if (! $result = create_note
	($note_title, $note_content, $isencrypt, $encrypt_pass,$creation_date, $uid)){
		echo "Here is what you get <br>";
		exit();
	}
	header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/main.php");
	exit();
}
?>

<html>
	<head>
		<title> My Note </title>
	</head>
	<body>
		<div>
			<a href="main.php">Back to main page</a>	
		</div>
		<div>
			<form action="<?php echo htmlspecialchars ($_SERVER['PHP_SELF']); ?>" method="post">
				<center>
				<div>
					<tr>						
						<td>Title:</td>
						<td>
							<input type="text" name="title">
						</td>
					</tr>
				</div>
				<br>
				<div>
					<tr>
						<td>
							<textarea name="content" rows="40" cols="100">Please input the content.</textarea>
						</td>
					</tr>
				</div>
				<br>
				<div>
					<tr>
						<td>encrypt pass:</td>
						<td>
							<input type="text" name="encrypt_pass">
						</td>
						<td>
							<input type="submit" name="submit" value="submit">
						</td>
					</tr>
				</div>
				</center>
			</form>
		</div>
	</body>
</html>
