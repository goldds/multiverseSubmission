<?php
if (!isset($_SESSION["user_id"])) {
    die("ACCESS DENIED");
}

if (isset($_POST["cancel"])) {
    header("Location: $url/inde.php");
    die();
}

if (isset($_POST["add"])) {{
    $position_validate = validatePos();
    $education_validate = validateEdu();
  
  
    if (strlen($_POST["first_name"]) < 1
        || strlen($_POST["last_name"]) < 1
        || strlen($_POST["email"]) < 1
        || strlen($_POST["headline"]) < 1
        
    ) {
        $_SESSION["error"] = "All fields are required";
        header("Location: $url/add.php");
        die();
    } 
    
    
    if ($education_validate !== true) {
        $_SESSION["error"] = $education_validate;
        header("Location: $url/add.php");
        die();
    }


    if ($position_validate !== true) {
        $_SESSION["error"] = $position_validate;
        header("Location: $url/add.php");
        die();
    }

   

  

    if (strpos($_POST["email"], "@") === false) {
        $_SESSION["error"] = "Email address must contain @";
        header("Location: $url/add.php");
        die();
    }
    $stmt = $pdo->prepare(
        'INSERT INTO profile
        (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)'
    );

    $stmt->execute(
        array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );

    $profile_id = $pdo->lastInsertId();
    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
        if (! isset($_POST['year'.$i]) ) {
            continue;
        }
        if (! isset($_POST['desc'.$i]) ) {
            continue;
        }
        $year = $_POST["year" . $i];
        $desc = $_POST["desc" . $i];
        $stmt = $pdo->prepare(
            'INSERT INTO position
            (profile_id, rank, year, description)
            VALUES ( :pid, :rank, :year, :desc)'
        );
        $stmt->execute(
            array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }

    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
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
                "INSERT INTO institution
                (name)
                VALUES (:school_name)"
            );
            $stmt->execute(array(':school_name' => $_POST["edu_school" . $i]));
            $instid = $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare(
            'INSERT INTO education
            (profile_id, institution_id, rank, year)
            VALUES (:pid, :instid, :rank, :year)'
        );
        $stmt->execute(
            array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':instid' => $instid)
        );
        $rank++;
    }
    
    $_SESSION["success"] = "Profile added";
    header("Location: $url/inde.php");
    die();
}}
////////////////////////////////////////////////////////




//If resume file is submitted
if (isset($_POST['file_add'])) {
  $id =( $_SESSION["user_id"]);
  $currentDirectory = getcwd();
  $folder_path = '/upload/storeddocs/';
  $filename = basename($_FILES['userfile']['name']);
  $test = $currentDirectory.$folder_path.$id.'1';
  $count = 1;
  $mimeType = pathinfo($_FILES["userfile"]['name'], PATHINFO_EXTENSION);
    
  if ($mimeType == "pdf")
  {
    //activated if a file is preiovusly submited
    if (file_exists($test)){
      $loop = 1; 
    while($loop == 1){
      $count += 1;
      $test[-1] = strval($count);

        //limits the amount of files one user can store
        if($count == 4){
            $_SESSION['error'] = 'User has reached files limit';
            header("Location: $url/inde.php");
            die();

            // if a new file name is found that meets the limit
        }elseif(!(file_exists($test))){
            $loop = 0;
        }
      }
    
  }
    //Incorrect file type
  }else{
    $_SESSION['error'] = 'Invalid file type';
            header("Location: $url/add.php");
            die();
  }

  
        //if move is sucuessly then user input will be tested and verfied. 
      if (move_uploaded_file($_FILES['userfile']['tmp_name'], $test))
      {print('test');
        // validation
        if (strlen($_POST["first_name"]) < 1
        || strlen($_POST["last_name"]) < 1
        || strlen($_POST["email"]) < 1
        || strlen($_POST["headline"]) < 1) 
        
        {
        $_SESSION["error"] = "All fields are required";
        unlink($uploadPath);
        header("Location: $url/add.php");
        die();
    } if (strpos($_POST["email"], "@") === false) {
      $_SESSION["error"] = "Email address must contain @";
      unlink($uploadPath);
      header("Location: $url/add.php");
      die();
  }
  $dirlen = strlen($currentDirectory.$folder_path);
  $stmt = $pdo->prepare(
      'INSERT INTO profile
      (user_id, first_name, last_name, email, headline, summary)
      VALUES ( :uid, :fn, :ln, :em, :he, :su)'
  );

  $stmt->execute(
      array(
      ':uid' => $_SESSION['user_id'],
      ':fn' => $_POST['first_name'],
      ':ln' => $_POST['last_name'],
      ':em' => $_POST['email'],
      ':he' => $_POST['headline'],
      ':su' => '/'.(substr($test, $dirlen))
  ));
    //if file was transfered sucessfully
    $_SESSION["success"] = "Resume file uploaded Sucessfully!";
    header("Location: $url/inde.php");
    die();
  }
}


?>