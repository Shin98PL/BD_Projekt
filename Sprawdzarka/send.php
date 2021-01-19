<?php

	session_start();
	
	if (!isset($_SESSION['userID']))
	{
		header('Location: index.php');
		exit();
	}
	
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Sprawdzarka-Wyślij</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='main.css'>
    <script src='main.js'></script>
</head>
<body>

<div class="wrapper">
        <div class="nav">
            <ol>
                <li><a href="myPage.php">Główna</a></li> 
                <li><a href="tasks.php">Zadania</a></li>
                <li><a href="send.php">Wyślij</a></li>
                <li><a href="solutions.php">Zgłoszenia</a></li>
                <li><a href="logout.php">Wyloguj się</a></li>
            </ol>

        </div>

<h1>Tu wyślesz nowe rozwiązanie!</h1><br>
<form action="wykonaj.php" method="post" enctype="multipart/form-data">
  Select file to upload:
  <br /><input type="file" name="fileToUpload" id="fileToUpload"><br />
  Zadanie:
  <br /><select id="task" name="taskID">

<?php
$host = "localhost";
$db_user = "root";
$db_passw = "";
$dbase = "BD";


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbase;charset=utf8", $db_user, $db_passw);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  }
  catch(PDOException $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił problem z połączeniem z bazą danych!</span>';
    header('Location: myPage.php');
    exit();
  }


$login = $_POST['login'];
$password = $_POST['password'];


try {

        
    $login = strtolower(trim($login));
    $password = strtolower(trim($password));
    
    $stmt = $conn->prepare("select id, name from task");
    $stmt->bindValue(":username", $login, PDO::PARAM_STR);
    $stmt->execute();
    while($res = $stmt->fetch(PDO::FETCH_OBJ))
    {
        echo "<option value={$res->id}>{$res->name}</option>";
    }


} catch (PDOException $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił problem z bazą danych! Spróbuj ponownie później!</span>';
    header('Location: myPage.php');
  
} catch (Exception $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił błąd! Spróbuj ponownie później!</span>';
    header('Location: myPage.php');
}

?>
<br /><br />
  <input type="submit" value="Upload" name="submit">
</form>

</body>
</html>