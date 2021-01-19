<?php

session_start();
	
if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
{
    header('Location: index.php');
    exit();
}

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
    header('Location: index.php');
    exit();
  }


$login = $_POST['login'];
$password = $_POST['password'];


try {

        
    $login = strtolower(trim($login));
    
    $stmt = $conn->prepare("select id as UserID, passw as Password from users where id = :username");
    $stmt->bindValue(":username", $login, PDO::PARAM_STR);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_OBJ);
    if (!$res || $password != $res->Password){
        $_SESSION['err'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
        header('Location: index.php');
    }
    else {
        $_SESSION['userID'] = $res->UserID;
        header('Location: myPage.php');
    }


} catch (PDOException $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił problem z bazą danych! Spróbuj ponownie później!</span>';
    header('Location: index.php');
  
} catch (Exception $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił błąd! Spróbuj ponownie później!</span>';
    header('Location: index.php');
}


?>