<?php
$server = 'localhost:3307';
$username = 'root';
$password = '';
$database = 'student_management_system';

if (isset($_POST))

    $conn = new mysqli($server, $username, $password, $database);
if ($conn) {
    // echo 'Server Connected Success';
} else {
    die(mysqli_error($conn));
}
