<?php
require_once "bootstrap.php";
require_once "pdo.php";

if (isset($_POST["cancel"])) {
  header("Location: $url/index.php");
  die();
}

if (isset($_POST["register"])) {
  //validation 
  if (strlen($_POST["name"]) < 1
      || strlen($_POST["email"]) < 1
      || strlen($_POST["password"]) < 1
      
  ) {
      $_SESSION["error"] = "All fields are required";
      header("Location: $url/registeration.php");
      die();
  } 

  if (strpos($_POST["email"], "@") === false) {
    $_SESSION["error"] = "Email address must contain @";
    header("Location: $url/registeration.php");
    die();
  }
  $stmt = $pdo->prepare(
    'INSERT INTO users
    (name, email, password)
    VALUES (:name, :em, :pw)'
);
$salt = 'XyZzy12*_';

$check = hash('md5', $salt.$_POST['password']);
$stmt->execute(
    array(
    ':name' => $_POST['name'],
    ':em' => $_POST['email'],
    ':pw' => ($check)
    ));
    $_SESSION["success"] = "Profile Registered!";
    header("Location: $url/index.php");
    die();
  }


?>
<!DOCTYPE html>
<html>
<head>
<title>jason joseph</title>
</head>
<body>


<div class="container">
  <h1> Register Page </h1>
    <form method="post">
      <br><p>
    <label>Full Name:</label>
      <input type = "text" name = "name"> 
      <p>
    <label>Email:</label>
      <input type="text" name = "email">
      <p>
      <label>Password:</label>
      <input type="text" name = password><br>
      <input type="submit" name="register" value="Register">
        <input type="submit" name="cancel" value="Cancel">
    </form>