<?php
$db_info = 'mysql:host=127.0.0.1;dbname=mydb';
$username = 'root';
$password = '';

$con = new PDO($db_info, $username, $password);

$sql = "CREATE TABLE IF NOT EXISTS users (
                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        username VARCHAR(8) NOT NULL,
                        password VARCHAR(256) NOT NULL,
                        email VARCHAR(50) NOT NULL,
                        attempts INT(1) DEFAULT 0 NOT NULL,
                        lockout TIMESTAMP NULL DEFAULT NULL
                        )";
$con->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS assessments (
                        uniqueID INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        id INT(6) UNSIGNED,
                        userID INT(6) NOT NULL,
                        filename VARCHAR(50) NOT NULL,
                        courseID VARCHAR(8) NOT NULL,
                        aType VARCHAR(50) NOT NULL,
                        aDate VARCHAR(20) NOT NULL,
                        aTime VARCHAR(20) NOT NULL,
                        status VARCHAR(20) NOT NULL
                        )";
$con->exec($sql);

?>