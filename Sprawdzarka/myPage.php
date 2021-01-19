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
    <title>Sprawdzarka-Główna</title>
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

<br>

<?php 
echo "<b style = \"color:Green; text-decoration: underline; font-size: 20px; \">
      Witaj ".strtoupper($_SESSION['userID']).'!</b>';

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
      $stmt = $conn->prepare("select count(tests_passed) as num, sum(tests_passed) as wyn 
                            from solution where userID = :userID and solution_date >= CURRENT_DATE()");
      $stmt->bindValue(":userID", $_SESSION['userID'], PDO::PARAM_STR);
      $stmt->execute();
      $res = $stmt->fetch(PDO::FETCH_OBJ);
      if(!$res || !isset($res->num) || !isset($res->wyn))
      {
        echo '<b>Nie znaleziono dzisiejszych rozwiązań!</b>';
      }
      else
      {
        echo "<h1>Twoje dzisiejsze zgłoszenia!</h1><br>"  ;
        echo "<b>Wysłano: </b><a>{$res->num}</a><br>";
        $allTests = $res->num*5;
        echo "<b>Przeszło testów: </b><a>{$res->wyn} / {$allTests}</a><br>";
      }
  
  
  } catch (PDOException $e) {
    echo '<span style="color:red">Wystąpił problem z bazą danych! Spróbuj ponownie później!</span>';
    
  } catch (Exception $e) {
    echo '<span style="color:red">Wystąpił błąd! Spróbuj ponownie później!</span>';
  }
  
  }


?>


</body>
</html>