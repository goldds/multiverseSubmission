<?php
require_once "pdo.php";
require_once "bootstrap.php"; 
session_start();
$failure = false;
$id = $_GET['id'];

$sql = "SELECT * FROM profile WHERE profile_id = :profile_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":profile_id" => $id));

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$fn = htmlentities($row["first_name"]);
$ln = htmlentities($row["last_name"]);

if (! isset($_SESSION['name']) && ! isset($_SESSION['user_id']) ) {
die('Not logged in');}

if (isset($_POST['confirmation'])){
$dir = getcwd();
    if (file_exists($dir . '/upload/storeddocs' .$row["summary"])){
    unlink(getcwd().'/upload/storeddocs'.$row["summary"]);}

    $stmt = $pdo->prepare("DELETE FROM Profile Where profile_id = '".$id."' ");
    $stmt->execute([$id]);
    
    $_SESSION['confirm'] = ( "Record Deleted ");
        header("Location: Inde.php");
    return;}


    if (isset($_POST['Cancel'])){
        header("Location: Inde.php");
        return;
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e03fb2e2</title>
</head>
<H1> Deleting Profile </H1>

<body>
    <p>First Name: <?php echo $fn ?></p>
    <p>Last Name: <?php echo $ln ?></p>
    <form method= "POST">
    <input type="submit" value="Delete" name="confirmation">
        <input type="submit" value="Cancel" name="Cancel">
</form>
</body>
</html>