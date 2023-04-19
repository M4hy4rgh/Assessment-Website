<?php
//<!--Student ID: 101399392-->
//<!--Student Name: Mahyar Ghasemi Khah-->
//<!--CLass: lecture: Tuesday 10-12, Lab: Wednesday 10-12-->
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

        main p {
            font-size: 17px;
            margin-left: .5em;
        }

        main > h1 {
            margin: 0;
            padding: .5em;
            background-color: #2F6690;
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

        a:hover {
            text-decoration: underline;
        }

        main input[type="submit"] {
            padding: 3px 9px 3px 9px;
            margin: 0 0 1em 1em;
            border-radius: 5px;
            font-family: 'Roboto Slab', Arial, serif;
            font-size: 13px;
        }

        table {
            margin: 0 0 0 .5em;
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
    <?php

    echo "<h1>List of Current Assessments:</h1>";
    $assessments = show_table_by_filter('Current'); // show the table of current assessments

    $tableHeadings = array("ID", "Course Code", "Assessment type", "Due Date", "Due Time"); //array of titles
    // of the table
    echo "<form action='controller.php'>";
    echo "<br><table><tr><th></th>";

    foreach ($tableHeadings as $heading) { // print the table headings
        echo "<th style='padding-left: 15px'>$heading</th>";
    }
    echo "</tr>";
    for ($i = 0; $i < count($assessments); $i++) { // print the table rows
        echo "<tr>";
        $find = $assessments[$i]['id'];
        echo "<td><input type='checkbox' name='ids[]' value='$find'></td>"; // print the checkbox
        foreach ($assessments[$i] as $key => $value) {
            echo "<td style='padding-left: 15px'>" . str_replace('"', "", $value) . "</td>"; // print table data
        }
        echo "</tr>";
    }

    echo "</table>";
    if (!$assessments) { // if there is no assessment in the table
        echo "<p>No current assessments</p>";
    }
    if (isset($_GET['ids'])) {// print the number of updated assessments
        echo "<p>" . count(show_table_by_filter('Current')) . " assessments are not marked Completed</p>";
        echo "<p>" . count($_GET['ids']) . " Assessment updated successfully.</p>";
    }
    echo "<br><input type='submit' name='action' value='Update_Current'>";
    echo "</from>";

    ?>
</main>
<script>
    document.getElementById("current").style.backgroundColor = "FireBrick";
</script>
</body>
</html>
