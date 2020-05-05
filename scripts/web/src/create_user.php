<?php
session_start();

if (isset($_SESSION["uid"])) { 
	header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/main.php"); exit(); }

require("db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$first_name = $_POST["first_name"];
	$last_name = $_POST["last_name"];
	$username = $_POST["username"];
	$password = $_POST["password"];

	if (! $result = create_user($first_name, $last_name, $username, $password)) {
		exit();
	}
	$_SESSION["uid"] = $result;
	header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/main.php");
	exit();
}
?>

<!DOCTYPE>
<html>
	<head>
		<title> My Note </title>
	</head>
	<body>
		<center>
			<div>
				<h2> Register </h2>
				<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
					<table>
						<tr>
							<td>First name:</td>
							<td>
								<input type="text" name="first_name">
							</td>
						</tr>
						<tr>
							<td>Last name:</td>
							<td>
								<input type="text" name="last_name">
							</td>
						</tr>
						<tr>
							<td>Username:</td>
							<td>
								<input type="text" name="username">
							</td>
						</tr>
						<tr>
							<td>Password:</td>
							<td>
								<input type="password" name="password"> 
 							</td>
 						</tr>
						<tr>
							<td>
								<input type="submit" name="submit" value="Register">
							</td>
							<td>
								Already has an account?
								<a href="login.php"> Login now </a>
							</td>
						</tr>
					</table>
				</form>
			</div>
		</center>
	</body>
</html>
