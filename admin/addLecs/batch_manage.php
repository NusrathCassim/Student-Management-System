<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if action is edit
    if ($_POST['action'] === 'edit') {
        // Retrieve values from POST
        $lecturer_id = mysqli_real_escape_string($conn, $_POST['username']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $dob = mysqli_real_escape_string($conn, $_POST['dob']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $nic = mysqli_real_escape_string($conn, $_POST['nic']);
        $contact = mysqli_real_escape_string($conn, $_POST['contact']);

        // Perform update query
        $sql = "UPDATE lecturers SET 
                name = '$name', 
                password = '$password', 
                department = '$department', 
                email = '$email', 
                dob = '$dob', 
                gender = '$gender', 
                nic = '$nic', 
                contact = '$contact' 
                WHERE username = '$lecturer_id'";

        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: addLecturer.php?message=edit");
            exit;
        } else {
            // Redirect with error message
            header("Location: addLecturer.php?message=error");
            exit;
        }
    } elseif ($_POST['action'] === 'delete') {
        // Handle delete action
        $lecturer_id = mysqli_real_escape_string($conn, $_POST['username']);

        // Perform delete query
        $sql = "DELETE FROM lecturers WHERE username = '$lecturer_id'";

        if (mysqli_query($conn, $sql)) {
            // Redirect with success message
            header("Location: addLecturer.php?message=delete");
            exit;
        } else {
            // Redirect with error message
            header("Location: addLecturer.php?message=error");
            exit;
        }
    } else {
        // Invalid action
        header("Location: addLecturer.php?message=error");
        exit;
    }
} else {
    // Redirect if accessed directly without POST request
    header("Location: addLecturer.php");
    exit;
}
?>
