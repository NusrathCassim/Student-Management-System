<?php
// Start the session
session_start();

// Include the database connection
include_once('../../connection.php');

// Loading the HTML template
include_once('../../assests/content/static/template.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../style-template.css"> <!--Template File CSS-->
    <link rel="stylesheet" href="style-recreation_mem.css"> <!--Relevent PHP File CSS-->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
</head>
<body>
    
<section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <h1 class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">Recreation Membership</h1>
                <div class="col-md-8 col-lg-7 col-xl-6">
                <img src="pics/recreation.png" class="img-fluid" alt="Phone image" height="300px" width="600px">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
                <form action="request_lib.php" method="post">
                    
                    <!-- <p class="text-center h1 fw-bold mb-4 mx-1 mx-md-3 mt-3">Login </p> -->

                    <!-- Username input -->
                    <div class="form-outline mb-4">
                    <label class="form-label" for="form1Example13"> <i class="bi bi-person-circle"></i> Username</label>
                    <input type="text" id="form1Example13" class="form-control form-control-lg py-3" name="username" autocomplete="off" placeholder="Enter your username" style="border-radius:25px ;" />

                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                    <label class="form-label" for="form1Example23"><i class="bi bi-person-vcard-fill"></i> Student Name</label>
                    <input type="text" id="form1Example23" class="form-control form-control-lg py-3" name="student_name" autocomplete="off" placeholder="Enter your name" style="border-radius:25px ;" />

                    </div>


                    <!-- Password input -->
                    <div class="form-outline mb-4">
                    <label class="form-label" for="form1Example23"><i class="bi bi-envelope-fill"></i> Email </label>
                    <input type="email" id="form1Example23" class="form-control form-control-lg py-3" name="email" autocomplete="off" placeholder="Enter your e-mail" style="border-radius:25px ;" />

                    </div>


                    <!-- Signin button -->
                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <input type="submit" value="Requset Membership" name="request_lib" class="btn btn-warning btn-lg text-light my-2 py-3" style="width:100% ; border-radius: 30px; font-weight:600; background-color: #3333ff; border-color: #3333ff; color: #FFFFFF;" />
                
                    </div>

                </form>
                </div>
            </div>
        
        </div>
    </section>
    
    
</body>
</html>