<?php
require "editing.php";
require "pdo.php";
session_start();
if (!isset($_SESSION["user_id"])) {
    print($_SESSION["user_id"]);
    die("ACCESS DENIED");
}

if (! isset($_GET['id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header("Location: $url/inde.php");
    die();
}


if (isset($_POST["cancel"])) {
    header("Location: $url/inde.php");
    die();
}

$sql = "SELECT * FROM profile WHERE profile_id = :profile_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":profile_id" => $_GET['id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header('Location: inde.php');
    die();
}

$fn = htmlentities($row["first_name"]);
$ln = htmlentities($row["last_name"]);
$em = htmlentities($row["email"]);
$he = htmlentities($row["headline"]);
$su = htmlentities($row["summary"]);
$profile_id = $_GET["id"];

$sql = "SELECT * FROM position WHERE profile_id = :profile_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":profile_id" => $_GET['id']));
$position_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT * FROM education WHERE profile_id = :profile_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(":profile_id" => $_GET['id']));
$education_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Jason Joseph</title>
</head>
<body">
    <h1>Editing Profile for <?php echo htmlentities($_SESSION["name"]) ?></h1>
    <?php
    if (isset($_SESSION["error"])) {
        echo('<p style="color: red;">' . $_SESSION["error"]);
        unset($_SESSION["error"]);
    }
    ?>
    <form enctype="multipart/form-data" method="post">
        <label>First Name:</label>
        <input type="text" name="first_name" value="<?php echo $fn ?>">
        <br>
        <label>Last Name:</label>
        <input type="text" name="last_name" value="<?php echo $ln ?>">
        <br>
        <label>Email:</label>
        <input type="text" name="email" value="<?php echo $em ?>">
        <br>
        <label>Headline:</label>
        <br>
        <input type="text" name="headline" value="<?php echo $he ?>">
        <br>
       
        <?php
        $file = '/upload/storeddocs'.($row['summary']);
        $uploaddir = getcwd().'/upload/storeddocs';
        if (file_exists($uploaddir.($row['summary']))){
            echo(
                '<embed id="preview" name= "newfile" src="'.$file .'" title = "resume '.$file[-1].'" width= "600" height = "400">'

            );}


        elseif (!(file_exists($uploaddir.($row['summary'])))){
            echo('<br>
            <div id = "fileSubmission">
            <label>Summary:</label>
            <br>
            <textarea
        
                name="summary"
                cols="100"
                rows="20"
                style="resize: none;"
            >
                '.$su.' 
            </textarea></div>'
            


        ); }

            //if education is present using else if since a file and edu rows can't be filled out together
        elseif ($education_rows !== false) {
                    echo '
                    <br>
                <label>Education:</label>
                <input type="button" value="+" id="plus_education" class="plus_button">
                <br>
                    <div id="edu_fields">' . "\n";
                
                foreach ($education_rows as $row) {
                    $stmt = $pdo->prepare(
                        "SELECT name
                        FROM institution
                        WHERE institution_id = :instid"
                    );
                    $stmt->execute(array(":instid" => $row['institution_id']));
                    $institution = $stmt->fetch(PDO::FETCH_ASSOC);
                    $rank = $row["rank"];

                    echo '<div id="edu' . $rank . '">' . "\n";
                    echo 
                        '<p>Year: <input type="text" name="edu_year' .
                        $rank .'" value="' . $row["year"] . '">' . "\n";
                    echo 
                        '<input type="button" value="-" onclick="$(\'#edu' .
                        $rank . '\').remove(); fix_education(); return false;">' .
                        "\n" . '</p>';
                    echo 
                        '<p>School: <input type="text" size="80" value="' .
                        $institution["name"] . '" name="edu_school' .
                        $rank . '" class="school" autocomplete="off"/></p>' . "\n";
                    echo '</div>' . "\n";
                } 
                    echo '</div>';}
                
        if (! empty($position_rows)){

            print_r($position_rows);

            echo('<label>Position:</label>
            <input type="button" value="+" id="plus_button">
            <br>
            <div id="position_fields">' . "\n");
        
        foreach ($position_rows as $row) {
            $rank = $row["rank"];
            echo '<div id="position' . $rank . '">' . "\n";
            echo 
                '<p>Year: <input type="text" name="year' .
                $rank .'" value="' . $row["year"] . '">' . "\n";
            echo 
                '<input type="button" value="-" onclick="$(\'#position' .
                $rank . '\').remove(); fix_position(); return false;">' .
                "\n" . '</p>';
            echo 
                '<textarea name="desc' . $rank . '" rows="8" cols="80">' .
                $row["description"] . '</textarea>' . "\n";
            echo '</div>' . "\n";
        }  
            echo '</div>';}
        ?>
        <input type="hidden" name="id" value="<?php echo $profile_id ?>"><br>
        <label for="replacement file">Replace this file:  </label>
        <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
        <input name="userfile" type="file" id= "replaceUpload" class = "replaceUpload"  accept= " application/pdf"/>
        <br>
        <input type="submit" name="save" value="Save" id= "fileSubmission">
        <input type="submit" name="cancel" value="Cancel">
    </form>
    <script type="text/javascript" src="js/Position.js" ></script>
    <script type="text/javascript" src="js/Docvalid.js" ></script>
</body>
</html>
