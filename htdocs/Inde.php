<?php
require_once "pdo.php";
require_once "bootstrap.php"; 
session_start();
if (! isset($_SESSION['name']) && ! isset($_SESSION['user_id']) ) {
    die('Not logged in');
  }
  $stmt = $pdo->query("SELECT profile_id, first_name, last_name, email, headline, summary FROM Profile WHERE user_id = {$_SESSION['user_id']} ORDER BY profile_id");
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<title> Jason Joseph </title>
<head></head><body>
    <h1>Jason Joseph's Resume Registry</h1>
    <BR>
    <P>
<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
if (isset($_SESSION['confirm']) ) {
    $confirm = $_SESSION['confirm'];
    echo '<body style= color: green; >'.$confirm.'</body>';
        unset($_SESSION['confirm']);

}
if (empty($rows)){
    echo "no rows found";}
    
else{   
    $count = 0;
    $uploaddir = getcwd().'/upload/storeddocs';
    $files = [];
    foreach($rows as $row){
        if (! file_exists($uploaddir.($row['summary']))){
            $count +=1;

        }
        if (file_exists($uploaddir.($row['summary']))){

            array_push($files, '/upload/storeddocs'.($row['summary']) );
            continue;
    }}


if($count > 0){
 echo('<table id= "past_submissions" border="1">
<th>Name</th>
<th>Headline</th>
<th>Action</th></tr>');
foreach ($rows as $row) {
    
    
    if (! file_exists($uploaddir.($row['summary']))){

    echo(
      "<tr>
       <td> <a href='view.php?id=" . $row["profile_id"] . "'>" .
       htmlentities($row['first_name'] . " " . $row['last_name']) . '</a></td>
       <td>' .htmlentities($row['headline']).'</td>
       <td> <a href="edit.php?id='.$row['profile_id'].'"> Edit</a> / <a href="delete.php?id='.$row['profile_id'].'">Delete </a>
      </tr>');}}
    
      echo('</table>');}}
      

    
?></P>

<br>

<a href="add.php">Add New Entry</a>
<br>
<a href= "logout.php"> Logout</a>
<?php
if(isset($files)){
    echo('<div id= "file_submissions" class = "grid-container">');
    foreach ($files as $file){
        echo('<div>
        <embed id="preview" src="'.$file .'" title = "resume '.$file[-1].'" width= "600" height = "250">
        <tr><td><h3> Resume '.$file[-1].'  </h3> <a href="edit.php?id='.$row['profile_id'].'"> Edit</a> / <a href="delete.php?id='.$row['profile_id'].'">Delete </a></tr>
        </div>');

    };
    echo('</div>');}
?>