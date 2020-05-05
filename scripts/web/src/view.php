<?php
session_start();

if (! isset($_SESSION["uid"])) {
	header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/login.php"); exit();
}

require("db.php");
$url = $_SERVER['REQUEST_URI'];
$url_components = parse_url($url);
parse_str($url_components['query'], $params);

$note_id = $params['note_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $decrypt_key = $_POST['decrypt_key'];
    $content = decrypt_note_message($note_id, $decrypt_key, $_SESSION["uid"]);
    $_SESSION['decrypted_content'] = $content;
    header("Location: " . "http://" . $_SERVER['HTTP_HOST'] . "/view.php?note_id=" . $note_id);
    exit();
}
?>

<!DOCTYPE>
<html>
    <head>
		<title> My Note </title>
	</head>
    <body>
        <?php
            if (! validate2viewNote($note_id, $_SESSION["uid"])) {
                echo "<h2> Authorization Error </h2>", "<br>";
                echo "<a href='main.php'>Back</a>";
            }
            else {
                $result = note_retrieve($note_id)[0];
                $title = $result[0];
                $content = $result[1];
                $creation_date = $result[2];
                $isencrypted = $result[3] == 1? "Encrypted":"";

                if($isencrypted && isset($_SESSION['decrypted_content'])) {
                    $isencrypted = "Decrypted";
                }

                if ($isencrypted) $title .= " " .  
                    "<h4>($isencrypted)</h4>";

                if (! isset($_SESSION['decrypted_content'])) {
                    $title .=  "<form method='post'>" . "<tr>" . "<td>" .
                    "<textarea name='decrypt_key' rows='1' cols='30'></textarea>" . "</td>" .
                    "<td><input type='submit' name='decrypt' value='decrypt'></td>" . "</tr>" . 
                    "</form>";
                } else {
                    $content = $_SESSION['decrypted_content'];
                }

                echo "<left>";
                echo "<a href='main.php'>Back to main</a>";
                echo "</left>";
                echo "<div>";
                echo "<center>";
                echo "<h2>$title</h2>";
                echo "</center>";
                echo "</div>";
                echo "<div>";
                echo "created at ", $creation_date, "GMT";
                echo "</div>";
                echo "<br>";
                echo "<div><center>";
                echo $content;
                echo "</center></div>";


                unset($_SESSION['decrypted_content']);
            }
        ?>
    </body>
</html>