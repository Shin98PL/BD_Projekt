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
    <title>Sprawdzarka-Rozwiązania</title>
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

<h1>Oto lista twoich zgłoszeń!</h1><br>

<form action="raport.php" method="post" enctype="multipart/form-data">
<table><tr><th>Zadanie</th><th>Data</th></tr>
<?php

$first = false;

$host = "localhost";
$db_user = "root";
$db_passw = "";
$dbase = "BD";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbase;charset=utf8", $db_user, $db_passw);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $first = true;
  }
  catch(PDOException $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił problem z połączeniem z bazą danych!</span>';
    exit();
  }

  if($first){
  try {
    $stmt = $conn->prepare("SELECT S.id as ID, S.solution_date as dat, T.name as Tname
                            FROM solution S JOIN task T on S.taskID = T.id
                            where S.userID = :id order by S.solution_date desc");
    $stmt->bindValue(":id", $_SESSION['userID'], PDO::PARAM_STR);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    foreach($res as $it)
    {
        echo "<tr><td>{$it->Tname}</td><td> <button type=\"submit\" value=\"{$it->ID}\" name=\"taskID\">{$it->dat}</button></td></tr>";
    }



} catch (PDOException $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił problem z bazą danych! Spróbuj ponownie później!</span>';
    header('Location: myPage.php');
    exit();
  
} catch (Exception $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił błąd! Spróbuj ponownie później!</span>';
    header('Location: myPage.php');
    exit();
}

}


?>
</form>


</body>
</html>