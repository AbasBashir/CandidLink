<?php

    require "CRUD-functions.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        $cc = new users();
        
        $checkEmail = $cc->checkEmail();

        if ($checkEmail === true) {
            
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.main .email-exists').style.display = 'block';
                    });
                </script>";

        } else if (is_string($checkEmail)) {

            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.main .error').innerHTML = '$checkEmail';
                    document.querySelector('.main .error').style.display = 'block';
                });
            </script>";

        }else{

            $catch = $cc->create();

            if ($catch === true) {
                
                echo "<script> window.location.href = 'login.php'; </script>"; die;

            }else {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.main .error').innerHTML = 'Failed to sign up. Please try again later.';
                    document.querySelector('.main .error').style.display = 'block';
                });
            </script>";
            }
        }
        
    }

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Signup</title>
</head>
<body>
    <?php require "header.php"; ?>
    
    <div class="main">
        <h2> Signup </h2>
        <form class="common-form" method="post">
            <?php echo "<span class='error'></span>";?>
            Email: <input type="email" name="email" placeholder="Example@example.com" required>
            <?php echo "<span class ='email-exists'>Email already exists</span>";?>
            Username: <input type="text" name="name" placeholder="Name" required>
            Password: <input type="password" name="password" placeholder="Password" required>
                    <input type="submit">
        </form>
    </div>
   
</body>
</html>