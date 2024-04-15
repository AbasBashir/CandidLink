<?php
        require "CRUD-functions.php";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $cc = new users();
            $catch = $cc->login();

            if (is_string($catch)) {

            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.main .error').style.display = 'block';
                        document.querySelector('.main .error').innerHTML = '$catch';
                    });
                </script>";

            }
            
        }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <?php require "header.php"; ?>
    
    <div class="main">
        <h2> Login </h2>
        <form class="common-form" method="post">
            
            <?php echo "<span class='error'></span>";?>
            Email: <input type="email" name="email" placeholder="Example@example.com" required>
            Password: <input type="password" name="password" placeholder="Password" required>
                    <input type="submit">
        </form>
    </div>
   
</body>
</html>