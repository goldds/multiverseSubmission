<?php
session_start();
require_once "head.php";
require_once "pdo.php";
require_once "util.php";
require_once "upload.php"; 


 ?>

<!DOCTYPE html>
<html>
<head>
  
    <title>Jason Joseph</title>
</head>
<body">
    <h1>Adding Profile for <?php echo htmlentities($_SESSION["name"]) ?></h1>
    <?php
    if (isset($_SESSION["error"])) {
        echo('<p style="color: red;">' . $_SESSION["error"]);
        unset($_SESSION["error"]);
    }
    //enctype="multipart/form-data"
    ?>
    <form  enctype="multipart/form-data" method="post">
        <label>First Name:</label>
        <input type="text" name="first_name">
        <br>
        <label>Last Name:</label>
        <input type="text" name="last_name">
        <br>
        <label>Email:</label>
        <input type="text" name="email">
        <br>
        <label>Headline:</label>
        <br>
        <input type="text" name="headline">
        <br>
        <div id = "manual_input">
        <label class = "input_label">Summary:</label>
        <br>
        <textarea
            id = "summary"
            name="summary"
            cols="100"
            rows="20"
            style="resize: none;">
        </textarea>
        <br>
        <label >Education:</label>
        <input class = "plus_button" type="button" value="+" id="plus_education" >
        <br>
        <div id="edu_fields"></div>
        <label  >Position:</label>
        <input type="button" value="+" id="plus_position" class="plus_button">
        <br>
        <div id="position_fields"></div>
        <input type="submit" name="add" value="Add">
        <input type="submit" name="cancel" value="Cancel">
</div>
    <!-- max files size -->
    <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="userfile" type="file" id= "resumeUpload" class = "resumeUpload" accept= " application/pdf"/>
    <input type="submit" name= "file_add" value="Send File" id = "fileSubmission" />
    </form>
    <embed id="preview" src="" width= "600" height = "500" title = "resume">

    <script type="text/javascript" src="js/position.js"> </script>
    <script type= "text/javascript" src="js/Docvalid.js">
        
    <?php $userfile = "<script> userfile </script>"?></script>

</body>
</html>