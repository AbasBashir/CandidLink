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
        <form method="post">

            Email: <input type="email" name="email" placeholder="Example@example.com" required>
            Password: <input type="password" name="password" placeholder="Password" required>
                    <input type="submit">
        </form>
    </div>
   
</body>
</html>