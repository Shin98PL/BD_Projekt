<?php

	session_start();
	
	if (!isset($_SESSION['userID']))
	{
		header('Location: index.php');
		exit();
    }
    if(!isset($_SESSION['raport']))
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
    <title>Sprawdzarka-Wynik</title>
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


<?php

$raport = json_decode($_SESSION['raport']);
$tests = $_SESSION['tests'];
unset($_SESSION['raport']);
unset($_SESSION['tests']);

if($raport->errno == 1)
{
    echo $raport->err;
}
else if($raport->errno == 2)
{
    echo "<h1>Rezultat:</h1><br><br>";
    echo "<b>Kompilacja: </b><a> {$raport->err} </a><br>";
}
else
{
    echo "<h1>Rezultat:</h1><br><br>";
    echo "<b>Kompilacja: </b><a>Sukces</a><br>";
    echo "<br>";
    for($i = 1; $i <= $tests; $i++)
    {
        echo "<b>Test {$i}: </b><a>{$raport->tests->$i}</a><br>";
    }
}

if($raport->errno != 1)
{

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
    echo '<span style="color:red">Wystąpił problem z połączeniem z bazą danych!</span>';
  }

  if($first){
    try {
      $stmt = $conn->prepare("INSERT INTO solution (id, userID, taskID, solution_date, tests_passed, raport) 
                                VALUES (:id, :userID, :taskID, CURRENT_TIME(), :mark, :rap)");
      $stmt->bindValue(":id", md5(rand()), PDO::PARAM_STR);
      $stmt->bindValue(":userID", $_SESSION['userID'], PDO::PARAM_STR);
      $stmt->bindValue(":taskID", $raport->task, PDO::PARAM_STR);
      $stmt->bindValue(":mark", $raport->passed, PDO::PARAM_INT);
      $stmt->bindValue(":rap", json_encode($raport), PDO::PARAM_STR);
      $stmt->execute();
  
  
  } catch (PDOException $e) {
    echo '<span style="color:red">Wystąpił problem z bazą danych! Spróbuj ponownie później!</span>';
    
  } catch (Exception $e) {
    echo '<span style="color:red">Wystąpił błąd! Spróbuj ponownie później!</span>';
  }
  
  }

}

?>

<br>
<a href = 'myPage.php'>Powrót</a>

</body>
</html>