<?php 
require_once "head.php";
require_once "pdo.php";
require_once "util.php";

$host = $_SERVER['HTTP_HOST'];
$ruta = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$url = "http://$host$ruta"; 



if (isset($_POST['save'])){
  if (($_FILES["userfile"])) {
  
    $sql = "SELECT * FROM profile WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(":profile_id" => $_GET['id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $file = $row["summary"];

      if(file_exists(getcwd().'/upload/storeddocs/'.$file)) {




        $currentDirectory = getcwd();
        $folder_path = '/upload/storeddocs/';

          unlink($currentDirectory.'/upload/storeddocs'.$file);
          move_uploaded_file($_FILES['userfile']['tmp_name'], $currentDirectory.'/upload/storeddocs'.$file);
        
      
        if (strlen($_POST["first_name"]) < 1
        || strlen($_POST["last_name"]) < 1
        || strlen($_POST["email"]) < 1
        || strlen($_POST["headline"]) < 1
    ) {
        $_SESSION["error"] = "All fields are required";
        header("Location: $url/edit.php?id=" . $_GET["id"]);
        die();
    }

    if (strpos($_POST["email"], "@") === false) {
        $_SESSION["error"] = "Email address must contain @";
        header("Location: $url/edit.php?id=" . $_Get["id"]);
        die();
    }

      $sql 
        = "UPDATE profile
        SET
        first_name = :fn,
        last_name = :ln,
        email = :em,
        headline = :he
      
        WHERE
        profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
        ':profile_id' => $_GET['id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline']
    ));
      
      
      
      
      
      


    }
      $_SESSION["success"] = "Profile updated";
      header("Location: $url/inde.php");
      die();}
  //////////////////////////////

  $position_validate = validatePos();
  $education_validate = validateEdu();
    if (($position_validate !== true) && (isset($desc))){
        $_SESSION["error"] = $position_validate;
        header("Location: $url/edit.php?id=" . $_POST["id"]);
        die();
    }

    if (($education_validate !== true) && (isset($school))){
        $_SESSION["error"] = $education_validate;
        header("Location: $url/edit.php?id=" . $_POST["id"]);
        die();
    }

    if (strlen($_POST["first_name"]) < 1
        || strlen($_POST["last_name"]) < 1
        || strlen($_POST["email"]) < 1
        || strlen($_POST["headline"]) < 1
        || strlen($_POST["summary"]) < 1
    ) {
        $_SESSION["error"] = "All fields are required";
        header("Location: $url/edit.php?id=" . $_GET["id"]);
        die();
    }

    if (strpos($_POST["email"], "@") === false) {
        $_SESSION["error"] = "Email address must contain @";
        header("Location: $url/edit.php?id=" . $_Get["id"]);
        die();
    }


      $_SESSION["error"] = "HHA";
      $sql 
        = "UPDATE profile
        SET
        first_name = :fn,
        last_name = :ln,
        email = :em,
        headline = :he,
        summary = :su
        WHERE
        profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(
        array(
        ':profile_id' => $_GET['id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary']
    ));
    
    
    

    $stmt = $pdo->prepare('DELETE FROM position WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_POST['id']));

    $rank = 1;
    for ($i=1; $i<=9; $i++) {
        if (! isset($_POST['year'.$i]) ) {
            continue;
        }
        if (! isset($_POST['desc'.$i]) ) {
            continue;
        }

        $year = $_POST["year" . $i];
        $desc = $_POST["desc" . $i];
        $stmt = $pdo->prepare(
            'INSERT INTO position (profile_id, rank, year, description)
            VALUES ( :pid, :rank, :year, :desc)'
        );
        $stmt->execute(
            array(
            ':pid' => $_REQUEST["id"],
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc
        ));
        $rank++;
    }

    $stmt = $pdo->prepare('DELETE FROM education WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_POST['id']));

    $rank = 1;
    for ($i=1; $i<=9; $i++) {
        if (! isset($_POST['edu_year'.$i]) ) {
            continue;
        }
        if (! isset($_POST['edu_school'.$i]) ) {
            continue;
        }
        $year = $_POST["edu_year" . $i];
        $stmt = $pdo->prepare(
            "SELECT institution_id
            FROM institution
            WHERE name = :edu_school"
        );
        $stmt->execute(array(':edu_school' => $_POST["edu_school" . $i]));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row !== false) {
            $instid = $row["institution_id"];
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO institution (name)
                VALUES (:school_name)"
            );
            $stmt->execute(array(':school_name' => $_POST["edu_school" . $i]));
            $instid = $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare(
            'INSERT INTO education (profile_id, institution_id, rank, year)
            VALUES (:pid, :instid, :rank, :year)'
        );
        $stmt->execute(
            array(
            ':pid' => $_POST["id"],
            ':rank' => $rank,
            ':year' => $year,
            ':instid' => $instid)
        );
        $rank++;
    }

    $_SESSION["success"] = "Profile updated";
    header("Location: $url/inde.php");
    die();
  }


?>