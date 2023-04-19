<?php
//<!--Mahyar Ghasemi Khah-->

if (!isset($_SESSION['userid'])) {
    header('Location: ../controller.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="eng">
<head>
    <meta charset="UTF-8">
    <title>Assignment 3</title>
    <meta name="description" content="This is Assignmen 3 for COMP1230.">
    <meta name="author" content="Mahyar Ghasemi Khah">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100;0,200;0,300;0,400;1,100;1,200;1,300;1,400&family=Roboto+Slab:wght@400;500;600;700&family=Slabo+27px&display=swap"
          rel="stylesheet">

    <style>
        body {
            margin: 0;
            background-color: #D9DCD6;

        }

        main {
            padding: 0;
            background-color: #81C3D7;
            border-bottom: 5px solid LightSlateGray;
        }

        main > *:not(ul) {
            padding: 0 .5em 0 .5em;

        }

        main > h1 {
            margin: 0;
            padding: .5em;
            background-color: #2F6690;
        }

        main > p {
            font-size: 17px;
        }

        nav {
            background-color: #16425B;
            padding: 2em;
            border-style: solid;
            border-color: LightSlateGray;
            border-width: 0 0 5px 0;
        }

        .navbar {
            font-family: 'Roboto Slab', Arial, serif;
            background: transparent;
            padding: 5px 30px 5px 30px;
            border-width: 3px;
            border-radius: 5% 5% 5% 5%;
        }

        .navbar:hover {
            background-color: #f2f2f2;
        }

        ul {
            text-decoration: none;

        }

        ul li a {
            text-decoration: none;
            color: black;
            font-size: 18px;
        }

        a:hover {
            text-decoration: underline;
        }

        main input[type="submit"] {
            padding: 2px 8px 2px 8px;
            border-radius: 5px;
            font-family: 'Roboto Slab', Arial, serif;
        }

        footer {
            background-color: #16425B;
            margin: 0 0 .5em 0;
            padding: .1em .5em 1.5em .5em;

        }

        footer a {
            color: white;
            text-decoration: none;
        }

    </style>
</head>
<body>
<?php
include "inc/nav.php";
?>
<main>
    <h1>Upload Files:</h1>
    <form method="post" enctype="multipart/form-data">
        <br><label for="fileToUpload">Upload a file:</label> <!-- this is the label for the file upload -->
        <input type="file" name="fileToUpload" id="fileToUpload"><br><br> <!-- this is the file upload -->
        <input type="submit" value="Upload File" name="submit"> <!-- this is the submit button -->
    </form>

    <?php
    $filter_file = filter_input(INPUT_POST, 'fileToUpload', FILTER_SANITIZE_SPECIAL_CHARS);
    // this is the filter for the file

    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
        // this is the if statement for the file upload (check whether the file is uploaded or not and if there is
        // an error or not)
        $file_content = file_get_contents($_FILES['fileToUpload']['tmp_name']); // getting the content of the file
        $entries = explode("\n", $file_content);
        $successful = 0;
        foreach ($entries as $entry) {
            $entry = explode(",", $entry);
            if (!empty($entry) && count($entry) == 6) {
                $id = $entry[0];
                $courseID = $entry[1];
                $aType = $entry[2];
                $aDate = $entry[3];
                $aTime = $entry[4];
                $status = $entry[5];
                $userID = $_SESSION['userid'];
                $fileName = $_FILES['fileToUpload']['name'];

                $invalidChar = false;
                foreach ($entry as $value) {
                    foreach (["<", ">", "?", "*"] as $char) {
                        if (strpos($value, $char)) {
                            echo "<p>Invalid format. $value cannot contain $char.</p>";
                            $invalidChar = true;
                        }
                    }
                }
                if (!is_numeric($id)) {
                    echo "<p>The ID $id must be a number.</p>";
                }
                else if (strlen($courseID) != 8) {
                    echo "<p>Error: Course ID $courseID must have 8 characters.</p>";
                }
                else if (is_numeric($aType)) {
                    echo "<p>Error: Assignment type $aType must be a string.</p>";
                }
                else if ($status != "Current" && $status != "Completed") {
                    echo "<p>Error: Status $status must be either Current or Completed.</p>";
                }
                else if (!$invalidChar) {
                    $query = "INSERT INTO assessments (id, userID, filename, courseID, aType, aDate, aTime, status) VALUES (:id,:userID,:fileName,:courseID,:aType,:aDate,:aTime,:status)";
                    $stmt = $con->prepare($query);
                    $stmt->execute(array(':id' => $id, ':userID' => $userID, ':fileName' => $fileName, ':courseID' => $courseID, ':aType' => $aType, ':aDate' => $aDate, ':aTime' => $aTime, ':status' => $status));
                    $successful++;
                }
            }
        }

        if ($successful) {
            echo "<p>$successful entries from " . $_FILES['fileToUpload']['name'] . " uploaded successfully.</p>"; //write a message with the
            $_SESSION['filename'] = $_FILES['fileToUpload']['name']; // set the session variable for the file name
        } else {
            echo "<p>File " . $_FILES['fileToUpload']['name'] . " failed to upload.</p>"; //write a message with the
        }

        // name of the file uploaded
        unset($_FILES['fileToUpload']); // unset the file
    }
    ?>
    <p>CSV file must be in the following format:
        id,Course_code,Assessment_type,Due_date,Due_time,status[Current/Completed]</p>

    <h1>Previously Uploaded File:</h1>
    <?php
    echo "<ul>";
    $sql = "SELECT DISTINCT filename FROM assessments WHERE userID = :userID";
    $stmt = $con->prepare($sql);
    $stmt->execute(array(':userID' => $_SESSION['userid']));
    $uploads = $stmt->fetchAll();
    if (!$uploads) { // print a message if there is no
        // file in the data folder
        echo "<p>No file uploaded yet.</p>";
    }
        // hyperlink the name of the previous file to their file.
    if ($uploads) {
        foreach ($uploads as $file) {
            $fileName = $file[0];
            $isCurrent = isset($_SESSION['filename']) && $_SESSION['filename'] == $fileName ? "(active)" : "";
            echo "<li><a href='controller.php?action=replace&file_to_replace=$fileName'>$fileName $isCurrent</a></li>";
        }
    }
    echo "</ul>";
    ?>
    <br>

</main>
<script>
//    give the navbar a different color when it is being used.
    document.getElementById("upload").style.backgroundColor = "FireBrick";
</script>
</body>
</html>

