<?php
if (!isset($_SESSION['userid'])) {
    header('Location: ../controller.php');
    exit();
}
?>
<!--Student ID: 101399392-->
<!--Student Name: Mahyar Ghasemi Khah-->
<!--CLass: lecture: Tuesday 10-12, Lab: Wednesday 10-12-->
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
    <h1>Add New Assessment:</h1>
    <form method="get" enctype="multipart/form-data">
        <fieldset>
            <legend>Add New</legend>
            <label for="a-cc">Assessment's Course Code:</label>
            <input type="text" name="a-cc" id="a-cc" minlength="8" maxlength="8" required />
            <br><br>
            <label for="a-type">Assessment's Type:</label>
            <input type="text" name="a-type" id="a-type" required />
            <br><br>
            <label for="a-name">Assessment's Date:</label>
            <input type="date" name="a-date" id="a-date" required />
            <br><br>
            <label for="a-name">Assessment's Time:</label>
            <input type="time" name="a-time" id="a-time" required />
            <br><br>
            <label for="a-name">Assessment's Status:</label>&nbsp;
            <input type="radio" id="a-current" name="a-status" value="Current" required>
            <label for="a-current">Current</label>&nbsp;
            <input type="radio" id="a-completed" name="a-status" value="Completed" required>
            <label for="a-completed">Completed</label>

            <br><br>
            <input type="submit" value="Add Assessment" name="submit"> <!-- this is the submit button -->
        </fieldset>
    </form>

    <?php
    if (!isset($_SESSION['filename'])) {
        echo "<p>Please first open a file to be able to write to it.</p>";
    } else {
        if (isset($_GET['submit']) && isset($_GET['a-cc']) && isset($_GET['a-type']) && isset($_GET['a-date']) && isset($_GET['a-time']) && isset($_GET['a-status'])) {

            $query = "SELECT MAX(id) FROM assessments where filename = :filename LIMIT 1";
            $stmt = $con->prepare($query);
            $stmt->execute(array(':filename' => $_SESSION['filename']));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $id = $result ? $result['id'] + 1 : 0;
            $courseID = $_GET['a-cc'];
            $aType = $_GET['a-type'];
            $aDate = $_GET['a-date'];
            $aTime = $_GET['a-time'];
            $status = $_GET['a-status'];
            $userID = $_SESSION['userid'];
            $fileName = $_SESSION['filename'];

            $invalidChar = false;
            foreach (["<", ">", "?", "*"] as $char) {
                if (strpos($id, $char) || strpos($courseID, $char) || strpos($aType, $char) || strpos($aDate, $char) || strpos($aTime, $char) || strpos($status, $char) || strpos($fileName, $char)) {
                    echo "<p>Invalid format. Fields cannot contain $char.</p>";
                    $invalidChar = true;
                }
            }

            $successful = 0;
            if (strlen($courseID) != 8) {
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
                $successful = 1;
            }

            if ($successful) {
                echo "<p>Assessment added successfully.</p>"; //write a message with the
            } else {
                echo "<p>Failed to add assessment.</p>"; //write a message with the
            }
        }
    }
    ?>
    <br>

</main>
<script>
//    give the navbar a different color when it is being used.
    document.getElementById("addNew").style.backgroundColor = "FireBrick";
</script>
</body>
</html>

