<?php

session_start();
	
if ((!isset($_POST['login'])) || (!isset($_POST['password'])))
{
    header('Location: newUser.php');
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
    header('Location: newUser.php');
    exit();
  }


$login = $_POST['login'];
$password = $_POST['password'];


try {

    if (!preg_match('/^\w{1,16}$/', $login))
    {
        $_SESSION['err'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
        header('Location: newUser.php');
        exit();
    }
    else if (strlen($password) < 6 || strlen($password) > 16)
    {
        $_SESSION['err'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
        header('Location: newUser.php');
        exit();
    }
        
    $login = strtolower(trim($login));

    $stmt = $conn->prepare("select count(*) from users where id = :username");
    $stmt->bindValue(":username", $login, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetchColumn() != 0)
    {
        $_SESSION['err'] = '<span style="color:red">Użytkownik o takim loginie już istnieje!</span>';
        header('Location: newUser.php');
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO users (id, passw) VALUES (:username, :pass)");
    $stmt->bindValue(":username", $login, PDO::PARAM_STR);
    $stmt->bindValue(":pass", $password, PDO::PARAM_STR);
    $stmt->execute();

    $_SESSION['err'] = '<span style="color:green">Konto założone!</span>';
    header('Location: index.php');


} catch (PDOException $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił problem z bazą danych! Spróbuj ponownie później!</span>';
    header('Location: newUser.php');
  
} catch (Exception $e) {
    $_SESSION['err'] = '<span style="color:red">Wystąpił błąd! Spróbuj ponownie później!</span>';
    header('Location: newUser.php');
}


?>