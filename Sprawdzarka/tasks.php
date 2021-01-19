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
    <title>Sprawdzarka-Zadania</title>
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

<h1>Tu znajdziesz treści zadań!</h1><br>

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
    echo '<span style="color:red">Wystąpił problem z połączeniem z bazą danych!</span>';
  }

  if($first){
  try {
    
    $stmt = $conn->prepare("select id as taskID, name as taskName, difficulty as LVL from task order by difficulty");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_OBJ);
    if (!$res){
        echo '<span style="color:red">Nie znaleziono zadań!</span>';
    }
    else {

        function lvl(int $x){
            if($x == 1){
                return "łatwe";
            }
            if($x == 2){
                return "średnie";
            }
            return "trudne";
        }

        function col(int $x){
            if($x == 1){
                return "LightGreen";
            }
            if($x == 2){
                return "Yellow";
            }
            return "Tomato";
        }

        echo "<table><tr><th>Zadanie</th><th>Poziom</th></tr>";
        foreach($res as $it)
        {
            $thislvl = lvl($it->LVL);
            $thiscol = col($it->LVL);
            echo "<tr><td><a href=tasks/{$it->taskID}/text.pdf > {$it->taskName} </a></td>
                    <td style=\"background-color:{$thiscol};\">{$thislvl}</td></tr>";
        }
    }


} catch (PDOException $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił problem z bazą danych! Spróbuj ponownie później!</span>';
    header('Location: myPage.php');
  
} catch (Exception $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił błąd! Spróbuj ponownie później!</span>';
    header('Location: myPage.php');
}

}


?>


</body>
</html>