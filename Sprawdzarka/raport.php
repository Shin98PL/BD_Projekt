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
    <title>Sprawdzarka-Raport</title>
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

$tID = $_POST["taskID"];

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
      $stmt = $conn->prepare("select raport from solution where id = :id");
      $stmt->bindValue(":id", $tID, PDO::PARAM_STR);
      $stmt->execute();
      $res = $stmt->fetch(PDO::FETCH_OBJ);
      if(!$res)
      {
        echo '<b>Nie znaleziono rozwiązania!</b>';
      }
      else
      {
        $raport = json_decode($res->raport);
        if($raport->errno == 2)
        {
            echo "<h1>Rezultat:</h1><br><br>";
            echo "<b>Kompilacja: </b><a> {$raport->err} </a><br>";
        }
        else
        {
            echo "<h1>Rezultat:</h1><br><br>";
            echo "<b>Kompilacja: </b><a>Sukces</a><br>";
            echo "<br>";
            for($i = 1; $i <= 5; $i++)
            {
                echo "<b>Test {$i}: </b><a>{$raport->tests->$i}</a><br>";
            }
        }
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