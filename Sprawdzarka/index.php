<?php

	session_start();
	
	if ((isset($_SESSION['userID'])))
	{
		header('Location: myPage.php');
		exit();
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Sprawdzarka-Zaloguj</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
</head>
<body>

<h1>Sprawdzarka - Zaloguj</h1><br>

<form action="login.php" method="post">
		Login: <br /> <input type="text" name="login" /> <br />
		Password: <br /> <input type="password" name="password" /> <br /><br />
		<input type="submit" value="Zaloguj" />
</form>

<?php
    if(isset($_SESSION['err']))	echo $_SESSION['err'];
    unset($_SESSION["err"]);
?>
<br /><br />
<a href="newUser.php">Stwórz konto</a>


</body>
</html>