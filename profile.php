<?php 
   require_once "CRUD-functions.php";
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

        <?php if (!empty($_GET['action']) && $_GET['action'] =='edit') {?>
                
            <h2> Edit </h2>
            <!-- The part that says enctype="multipart/form-data" this is what allows us to upload an image -->
            <form method="post" enctype="multipart/form-data" class="sign-upForm">

                <img id="img-profile" src="<?php echo $_SESSION['info']['image'] ?>">
                Image: <input type="file" name="image"><br>
                <input value="<?php echo $_SESSION['info']['username'] ?>" type="text" name="username" placeholder="Username" required><br>
                <input value="<?php echo $_SESSION['info']['password'] ?>" type="text" name="password" placeholder="Password" required><br>
                <input value="<?php echo $_SESSION['info']['email'] ?>" type="email" name="email" placeholder="Email" required><br>

                <!-- <button>Save</button>
                <a href="profile.php">
                    <button type="button">Cancel</button>
                </a> -->
            </form>            

        <?php } else {?>
         
        <h2> User Profile </h2>
        <div class="profile-wrapper">

            <div class="profile">
                    <div>
                        <img id="img-profile" src="<?php echo $_SESSION['info']['image'] ?>">
                    </div>
                    <div>
                        <?php echo $_SESSION['info']['username']?>
                    </div>
                    <div>
                        <?php echo $_SESSION['info']['email']?>
                    </div>

                    <div class="btns">
                        <a href="profile.php?action=edit">
                            <button>Edit Profile</button>
                        </a>
                        <a href="profile.php?action=delete">
                            <button>Delete Profile</button>
                        </a>
                    </div>

                    <div class="post">
                        <h5>Create a post: </h5>
                        <form id="profile-form" method="post" >

                            <textarea name="post" cols="40" rows="6"></textarea><br>

                            <button class="post-btn">Post</button>   
                        </form>
                    </div>
                <hr>
            </div>

        </div>
        <?php } ?>
    </div>
   
</body>
</html>