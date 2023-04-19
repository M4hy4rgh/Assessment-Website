<?php
//<!--Mahyar Ghasemi Khah-->
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'model/initDB.php'; // include the initDB file
include 'model/functions.php'; // include the functions file

$errorMes = ""; // error message

//Credentials Authenticated
if (isset($_POST['username']) && isset($_POST['login-email']) && isset($_POST['login-password'])) {
    $username = $_POST['username'];
    $email = $_POST['login-email'];
    $password = password_hash($_POST['login-password'], PASSWORD_DEFAULT);

    $sql = "SELECT * FROM users WHERE username=:username AND email=:email LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->execute(array(':username' => $username, ':email' => $email));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userID = $user['id'];
        $query = "SELECT TIMESTAMPDIFF(SECOND, lockout, CURRENT_TIMESTAMP) AS difference FROM users WHERE id=:id";
        $stmt = $con->prepare($query);
        $stmt->execute(array('id' => $userID));
        $timeDiff = $stmt->fetch(PDO::FETCH_ASSOC);;

        if ($timeDiff['difference'] && $timeDiff['difference'] < 60) {
            $errorMes = 'You have been locked out for 60 seconds.';
        } else if (password_verify($_POST['login-password'], $user['password'])) {
            $query = "UPDATE users set attempts=0 WHERE id=:id";
            $stmt = $con->prepare($query);
            $stmt->execute(array(':id' => $userID));

            $_SESSION['userid'] = $user['id'];
            $_SESSION['timeout'] = time();
            setcookie("username", $user['username']);

        } else {
            $errorMes = 'Invalid username or password.';
            $userAttempts = $user['attempts'] + 1;
            if ($userAttempts == 3) {
                $userAttempts = 0;
                $query = "UPDATE users set lockout = CURRENT_TIMESTAMP WHERE id=:id";
                $stmt = $con->prepare($query);
                $stmt->execute(array(':id' => $userID));
            }
            $query = "UPDATE users set attempts=:attempts WHERE id=:id";
            $stmt = $con->prepare($query);
            $stmt->execute(array(':attempts' => $userAttempts, ':id' => $userID));
        }
    } else {
        $errorMes = 'Invalid username or password.';
    }
}

//If a user has already been registered, don’t allow the user to register for a second time.
if (isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['signup-email']) && isset($_POST['password']) && isset($_POST['confirmedPassword'])) {
    $username = $_POST['username'];
    $email = $_POST['signup-email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmedPassword'];

    if ($password != $confirmPassword) {
        $errorMes = 'Password and password confirmation do not match.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || strlen($username) < 4 || strlen($username) > 8) {
        $errorMes = 'Something went wrong.';
    } else {
        $sql = "SELECT * FROM users WHERE username=:username OR email=:email LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->execute(array(":username" => $username, "email" => $email));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['username'] === $username) {
            $errorMes = 'Username already exists.';
        } else if ($user && $user['email'] === $email) {
            $errorMes = 'Email already exists.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
            $stmt = $con->prepare($query);
            $result = $stmt->execute(array(":username" => $username, ":password" => $hashedPassword, ":email" => $email));

            if ($result) {
                $sql = "SELECT id FROM users WHERE username=:username AND email=:email LIMIT 1";
                $stmt = $con->prepare($sql);
                $stmt->execute(array(":username" => $username, "email" => $email));
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $userID = $user['id'];

                $_SESSION['userid'] = $userID;
                $_SESSION['timeout'] = time();
                setcookie("username", $username);
            } else {
                $errorMes = 'Registration failed, database error';
            }
        }
    }
}

if (!isset($_SESSION['userid'])) {
    include 'view/register.php'; // if the user is not logged in, show the login page
} else {
    if (time() - $_SESSION['timeout'] > 1800) {
        session_unset();
        session_destroy();
        include 'view/register.php';
    }
    $_SESSION['time'] = time();


    if (!isset($_SESSION['filename'])) {
        $sql = "SELECT DISTINCT filename FROM assessments WHERE userID=:userID";
        $stmt = $con->prepare($sql);
        $stmt->execute(array(':userID' => $_SESSION['userid']));
        $upload = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($upload) {
            $_SESSION['filename'] = $upload['filename'];
        }
    }

    $page_action = (!empty($_GET['action'])) ? $_GET['action'] : ((isset($_GET['submit']) && $_GET['submit'] == "Add Assessment") ? 'Add New Assessment' : ''); // get the action from the URL

    switch ($page_action) { // switch statement to determine which action to take
        case 'Completed':
        {
            include 'view/completed.php'; // include the completed view
            break;
        }
        case 'replace': //replace the data used when action is replace
        {
            $_SESSION['filename'] = $_GET['file_to_replace'];
            include 'view/upload.php'; // stay on the upload view
            break;
        }
        case 'Upload':
        {
            include 'view/upload.php'; // include the upload view
            break;
        }
        case 'Update_Completed': //update the data used when action is update_completed
        {
            if (isset($_GET['ids'])) {
                $ids = $_GET['ids'];
                change_status($ids);
            }
            include 'view/completed.php'; // stay on the completed view
            break;
        }
        case 'Update_Current': //update the data used when action is update_current
        {
            if (isset($_GET['ids'])) {
                $ids = $_GET['ids'];
                change_status($ids);
            }
            include 'view/current.php'; // stay on the current view
            break;
        }
        case 'Add New Assessment':
        {
            include 'view/addNew.php'; // include the add new assessment view
            break;
        }
        case 'Logout':
        {
            session_unset();
            session_destroy();
            unset($_GET['action']);

            echo "<script type='text/javascript'>window.location.href = 'controller.php';</script>";
            break;
        }
        case 'Current': //default action which is current
        default:
        {
            include 'view/current.php'; // include the current view
            break;
        }
    }
}
include "inc/footer.php";  // include the footer
?>
