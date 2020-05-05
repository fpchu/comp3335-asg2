<?php
session_start();

if (isset($_SESSION["uid"])) { 
	header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/main.php"); exit(); }

require("db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	if (! $result = login_validation($username, $password)) {
		echo $result;
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
				<h2> Login Here </h2>
				<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
					<table>
						<tr>
							<td>Username:</td>
							<td>
								<input type="text" name="username" id="username">
							</td>
						</tr>
						<tr>
							<td>Password:</td>
							<td>
								<input type="password" name="password" id="password">	
							</td>
						</tr>
						<tr>
							<td>
								<input type="submit" name="submit" value="Login" id="login_button">
							</td>
							<td>
								Want an account?
								<a href="create_user.php">Register</a>
							</td>
						</tr>
					</table>
				</form>
			</div>				
		</center>	
		<script>
			//
		</script>
	</body>	
</html>

