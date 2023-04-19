<!--Student ID: 101399392-->
<!--Student Name: Mahyar Ghasemi Khah-->
<!--CLass: lecture: Tuesday 10-12, Lab: Wednesday 10-12-->
<?php
function getCurrentFile() //This is the function that gets the current file.
{
    $currentFile = "";
    if (isset($_SESSION['filename'])) {
        $currentFile = $_SESSION['filename'];
    }
    return $currentFile;
}


function show_table_by_filter($page_action) //this is the function that shows the table by page_action as its filter.
{
    $assessments = [];
    $current_file = getCurrentFile();
    if ($current_file) {
        $read_file = read_data($current_file);
        foreach ($read_file as $assessment) {
            if ($assessment['status'] == $page_action) {
                unset($assessment['status']);
                $assessments[] = $assessment;
            }
        }
    }
    return $assessments;
}

function read_data($current_file) //this is the function that reads the data from the file.
{
    global $con;
    $current_file = getCurrentFile();
    $query = "SELECT DISTINCT id,courseID,aType,aDate,aTime,status FROM assessments WHERE filename=:filename AND userID=:userID"; //this is the query that gets the data from the database.
    $stmt = $con->prepare($query);
    $stmt->execute(array(':filename' => $current_file, ':userID' => $_SESSION['userid']));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function change_status($ids) //this is the function that changes the status of the assessments.
{
    global $con;
    $current_file = getCurrentFile();
    foreach ($ids as $id) {
        $query = "SELECT * FROM assessments WHERE userID=:userID AND filename=:filename AND id=:id";
        $stmt = $con->prepare($query);
        $stmt->execute(array(':userID' => $_SESSION['userid'], ':filename' => $current_file, ':id' => $id));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $newStatus = $result['status'] == "Completed" ? "Current" : "Completed";
            $query = "UPDATE assessments set status=:status where id=:id";
            $stmt = $con->prepare($query);
            $stmt->execute(array(':status' => $newStatus, ':id' => $id));
        }
    }
}

?>