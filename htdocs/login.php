<?php // Do not put any HTML above this line
session_start();
require_once "pdo.php";

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

if (isset($_POST["email"]) && isset($_POST["pass"])) {
unset($SESSION["name"]);
unset($SESSION["user_id"]);

$salt = 'XyZzy12*_';

$check = hash('md5', $salt.$_POST['pass']);
$stmt = $pdo->prepare('SELECT user_id, name FROM users

         WHERE email = :em AND password = :pw');

$stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));

$row = $stmt->fetch(PDO::FETCH_ASSOC);
// Check to see if we have some POST data, if we do process it

if (!$row === false) {


        $_SESSION['name'] = $row['name'];

        $_SESSION['user_id'] = $row['user_id'];

        // Redirect the browser to index.php

        header("Location: inde.php");
        return;
    }
else {
        $_POst['failure'] = ("Incorrect password");
        $_SESSION['failure'] = $_POst['failure'];
        header("Location: login.php");
        return;
    }}

// Fall through into the View
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jason Joseph's Login Page</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h1>Please Log In</h1>
    <form method="POST" action="login.php">
        <label for="email">Email</label>
        <input type="text" name="email" id="email"><br/>
        <label for="id_1723">Password</label>
        <input type="password" name="pass" id="id_1723"><br/>
        <input type="submit" onclick="return doValidate();" value="Log In">
        <input type="submit" name="cancel" value="Cancel">
    </form>
    <p>
        For a password hint, view source and find an account and password hint
        in the HTML comments.
        <!-- Hint:
        The account is umsi@umich.edu
        The password is the three character name of the
        programming language used in this class (all lower case)
        followed by 123. -->
    </p>
    <script>
        function doValidate() {
            console.log('Validating...');
            try {
                addr = document.getElementById('email').value;
                pw = document.getElementById('id_1723').value;
                console.log("Validating addr="+addr+" pw="+pw);
                if (addr == null || addr == "" || pw == null || pw == "") {
                    alert("Both fields must be filled out");
                    return false;
                }
                if ( addr.indexOf('@') == -1 ) {
                    alert("Invalid email address");
                    return false;
                }
                return true;
            } catch(e) {
                return false;
            }
            return false;
        }
    </script>

</div>
</body>