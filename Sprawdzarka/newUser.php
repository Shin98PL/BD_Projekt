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
    <title>Sprawdzarka-Nowe Konto</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
</head>
<body>

<b>Nowe konto:</b><br /><br />

<form action="create.php" method="post">
		Login: <br /> <input type="text" name="login" /> <br />
		Password: <br /> <input type="password" name="password" /> <br /><br />
		<input type="submit" value="Załóż" />
</form>
<?php
    if(isset($_SESSION['err']))	echo $_SESSION['err'];
    unset($_SESSION["err"]);
?>
<br /><br />
<b>Login powinien:</b>
<p>zawierać od 1 do 16 znaków.</p>
<p>zawierać tylko litery, liczby i znak _ .</p>
<p>Wszystkie białe spacje w loginie są usuwane.</p>
<p>Wielkość liter nie ma znaczenia</p><br />
<b>Hasło powinno się składać od 6 do 16 znaków.</b><br />
<br />
<a href="index.php">Powrót do logowania</a>

</body>
</html>