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
                
                <h2> Edit Profile </h2>

                <div class="profile-wrapper edit-wrapper">

                        <div class="profile edit-profile">

                            <!-- The part that says enctype="multipart/form-data" this is what allows us to upload an image -->
                            <form method="post" enctype="multipart/form-data" class="common-form edit-form">
                            
                                <img id="edit-profile" src="<?php echo $_SESSION['info']['image'] ?>">

                                <div class="main-label">
                                    <div class="label img-file">
                                        Image: <input type="file" name="image">
                                    </div>
                                    <div class="label">
                                        Name: <input value="<?php echo $_SESSION['info']['username'] ?>" type="text" name="username" placeholder="Username" required>
                                    </div>
                                    <div class="label">
                                        Password: <input type="password" name="password" placeholder="Password" required>
                                    </div>
                                    <div class="label">
                                        Email: <input type="email" name="email" placeholder="Email" required><br>
                                    </div>
                                </div>
                                
                                <div class="btns edit-btns">
                                    <button class="save-btn">Save</button>
                                    <a id="cancel-btn-wrapper" href="profile.php">
                                        <button class="cancel" type="button">Cancel</button>
                                    </a>
                                </div>
                              
                            </form>            
                        </div>
                </div>

        <?php } else if (!empty($_GET['action']) && $_GET['action'] == 'delete' ) {?>

            <h2> Are You sure you want to delete your profile?! </h2>

            <div class="profile-wrapper edit-wrapper">

                    <div class="profile edit-profile">

                        <!-- The part that says enctype="multipart/form-data" this is what allows us to upload an image -->
                        <div class="common-form edit-form">
                        
                            <img id="edit-profile" src="<?php echo $_SESSION['info']['image'] ?>">

                            <div class="main-label delete-label">
                                    <div> <?php echo $_SESSION['info']['username'] ?> </div>
                                    <div> <?php echo $_SESSION['info']['email'] ?> </div>
                            </div>
                            
                            <div class="btns edit-btns delete-btn">
                                <button class="save-btn">delete</button>
                                <a id="cancel-btn-wrapper" href="profile.php">
                                    <button class="cancel" type="button">Cancel</button>
                                </a>
                            </div>
                            
                        </div>            
                    </div>
            </div>
        <?php } else { ?>
        
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