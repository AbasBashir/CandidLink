<?php 
   require_once "CRUD-functions.php";
    
   $originalPostID = '';

   if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_GET['action']) && $_GET['action'] == "post_deleteReply")
   {
            $cc = new users();

            $deletePost = $cc->deleteReply();

            if (is_string($deletePost)) {

                    echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.profile-error').style.display = 'block';
                        document.querySelector('.profile-error').innerHTML = '$deletePost';
                    });
                </script>";
            }else{
                header("Location: profile.php");
                die;
            }
          
   } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_GET['action']) && $_GET['action'] == "post_editReply")
   {
        $cc = new users();
        $updatePost = $cc->editReply();

        if (is_string($updatePost)) {

                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.profile-error').style.display = 'block';
                    document.querySelector('.profile-error').innerHTML = '$updatePost';
                });
            </script>";
        }else{

            if (isset($_GET['main']) && $_GET['main'] == "trending") {

                header("Location: index.php");
                die;
            }else{
                header("Location: profile.php");
                die;

            }

        }
   
   
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && !empty($_POST['reply_post'])){

        $originalPostID = $_POST['action'];
        
        $reply = $_POST['reply_post'];

        $cc = new users();

        $replyAnswer = $cc->addReply($reply, $originalPostID, $_SESSION['info']['id']);

        if (is_string($replyAnswer)) {

            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.profile-error').style.display = 'block';
                    document.querySelector('.profile-error').innerHTML = '$replyAnswer';
                });
            </script>";
        }else{
            header("Location: profile.php");
            die;
        }

   }else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_GET['action']) && $_GET['action'] == "post_delete")
   {
            $cc = new users();

            $deletePost = $cc->deletePost();

            if (is_string($deletePost)) {

                    echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.profile-error').style.display = 'block';
                        document.querySelector('.profile-error').innerHTML = '$deletePost';
                    });
                </script>";
            }else{
                header("Location: profile.php");
                die;
            }
          
   } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_GET['action']) && $_GET['action'] == "post_edit")
   {
        $cc = new users();

        $updatePost = $cc->editPost();

        if (is_string($updatePost)) {

                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.profile-error').style.display = 'block';
                    document.querySelector('.profile-error').innerHTML = '$updatePost';
                });
            </script>";
        }else{

            if (isset($_GET['main']) && $_GET['main'] == "trending") {

                header("Location: index.php");
                die;
            }else{
                header("Location: profile.php");
                die;

            }

        }
   
   
    }else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['post']))
    {
        $cc = new users();

        $check = $cc->addingPost();
        
        if (is_string($check)) {

            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.profile-error').style.display = 'block';
                        document.querySelector('.profile-error').innerHTML = '$check';
                    });
                </script>";

        }else {

            header("Location: profile.php");
            die;

        }


    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && $_POST['action'] == 'delete') 
    {
        // Delete profile
        $cc = new users();

        $check = $cc->deleteProfile();
        
        if (is_string($check)) {

            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.profile-error').style.display = 'block';
                        document.querySelector('.profile-error').innerHTML = '$check';
                    });
                </script>";

        }else {

            header("Location: logout.php"); die;
        }


    }else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['username'])) 
        {
            
            //profile edit 
            $cc = new users();

            $check = $cc->editProfile();

            if (is_string($check)) {

                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelector('.profile-error').style.display = 'block';
                            document.querySelector('.profile-error').innerHTML = '$check';
                        });
                    </script>";

            }else {
                header("Location: profile.php");
                die;
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

    <?php if (!empty($_GET['action']) && $_GET['action'] =='post_deleteReply' && !empty($_GET['id'])) {?>
                    <?php

                        $conn = new users();
                    
                        $id = (int)$_GET['id'];
        
                        $read = "select * from replies where id = '$id' limit 1";
                        $connection = $conn->getConn();
                        $stmt = $connection->prepare($read);
                        $stmt->execute();
                        
                        $row =  $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <?php   if ($stmt->rowCount() > 0):?>
                        
                        <h2>Are You sure you want to delete this Reply?! </h2>
                        <form class="edit-post-form delete-post-form" method="post">
                        <div class="main-post">
                            <div class="content"><?php echo $row['reply'] ?></div><br>
                        </div>
                        <input type="hidden" name="action" value="post_deleteReply">
                            
                        <div class="btns edit-btns post-edit-btn-wrapper">
                            <button class="save-btn">Delete</button>
                            <a id="cancel-btn-wrapper" href="profile.php">
                                <button class="cancel" type="button">Cancel</button>
                            </a>
                        </div>
                        </form>
                    <?php endif;?>
            
            <?php } else if (!empty($_GET['action']) && $_GET['action'] =='post_editReply' && !empty($_GET['id'])) {?>

                <?php

                    $conn = new users();
                    
                    $id = (int)$_GET['id'];

                    $read = "select * from replies where id = '$id' limit 1";
                    $connection = $conn->getConn();
                    $stmt = $connection->prepare($read);
                    $stmt->execute();

                    $row =  $stmt->fetch(PDO::FETCH_ASSOC);

                    $read = "select post from posts where id = '$row[post_id]' limit 1";
                    $connection = $conn->getConn();
                    $stmt = $connection->prepare($read);
                    $result = $stmt->execute();

                    $originalPost =  $stmt->fetch(PDO::FETCH_ASSOC);

                ?>


                <?php   if ($stmt->rowCount() > 0):?>

                    <h4>Edit a Reply </h4>
                    <form class="edit-post-form" method="post">
                        <div class="original-post"><?php echo $originalPost['post'] ?></div>
                        <textarea name="post" cols="40" rows="6"><?php echo $row['reply'] ?></textarea><br>
                        <input type="hidden" name="action" value="post_editReply">
                            
                        <div class="btns edit-btns post-edit-btn-wrapper">
                            <button type="submit" class="save-btn">Save</button>
                            <a id="cancel-btn-wrapper" href="profile.php">
                                <button class="cancel" type="button">Cancel</button>
                            </a>
                        </div>
                    </form>

                <?php endif;?>
                    
            
            <?php }else if (!empty($_GET['action']) && $_GET['action'] =='post_delete' && !empty($_GET['id'])) {?>
                    <?php

                        $conn = new users();
                    
                        $id = (int)$_GET['id'];
        
                        $read = "select * from posts where id = '$id' limit 1";
                        $connection = $conn->getConn();
                        $stmt = $connection->prepare($read);
                        $stmt->execute();
                        
                        $row =  $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <?php   if ($stmt->rowCount() > 0):?>
                        
                        <h2>Are You sure you want to delete this post?! </h2>
                        <form class="edit-post-form delete-post-form" method="post" enctype="multipart/form-data" >
                        <div class="main-post">
                            <img src="<?php echo $row['image'] ?>" id="post-image2" class="post-delete-image"><br>
                            <div class="content"><?php echo $row['post'] ?></div><br>
                        </div>
                        <input type="hidden" name="action" value="post_delete">
                            
                        <div class="btns edit-btns post-edit-btn-wrapper">
                            <button class="save-btn">Delete</button>
                            <a id="cancel-btn-wrapper" href="profile.php">
                                <button class="cancel" type="button">Cancel</button>
                            </a>
                        </div>
                        </form>
                    <?php endif;?>
            
            <?php } else if (!empty($_GET['action']) && $_GET['action'] =='post_edit' && !empty($_GET['id'])) {?>

                <?php

                    $conn = new users();
                    
                    $id = (int)$_GET['id'];

                    $read = "select * from posts where id = '$id' limit 1";
                    $connection = $conn->getConn();
                    $stmt = $connection->prepare($read);
                    $stmt->execute();

                    $row =  $stmt->fetch(PDO::FETCH_ASSOC);

                ?>


                <?php   if ($stmt->rowCount() > 0):?>

                    <h4>Edit a post </h4>
                    <form class="edit-post-form" method="post" enctype="multipart/form-data" >
                        <img src="<?php echo $row['image'] ?>" id="post-image2"><br>
                        <div class="image-file-wrapper"><input type="file" name="image"></div><br>
                        <textarea name="post" cols="40" rows="6"><?php echo $row['post'] ?></textarea><br>
                        <input type="hidden" name="action" value="post_edit">
                            
                        <div class="btns edit-btns post-edit-btn-wrapper">
                            <button type="submit" class="save-btn">Save</button>
                            <a id="cancel-btn-wrapper" href="profile.php">
                                <button class="cancel" type="button">Cancel</button>
                            </a>
                        </div>
                    </form>

                <?php endif;?>
                    
            
            <?php } else if (!empty($_GET['action']) && $_GET['action'] =='edit') {?>
                    
                    <h2> Edit Profile </h2>

                    <div class="profile-wrapper edit-wrapper">

                            <div class="profile edit-profile">
                                <form method="post" enctype="multipart/form-data" class="common-form edit-form">
                                
                                    <img id="edit-profile" src="<?php echo $_SESSION['info']['image'] ?>"> 
            
                                    <div class="main-label">
                                        <div class="label img-file"> 
                                            Image: <input type="file" name="image"><?php echo "<span class='profile-error'></span>";?>
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

                            <form method="post" class="common-form edit-form">
                            
                                <img id="delete-page-profile" class="delete-page-profile" src="<?php echo $_SESSION['info']['image'] ?>">

                                <div class="main-label delete-label">
                                        <div> <?php echo $_SESSION['info']['username'] ?> </div>
                                        <div> <?php echo $_SESSION['info']['email'] ?> </div>
                                        <input type="hidden" name="action" value="delete">
                                </div>
                                
                                <div class="btns edit-btns delete-btn">
                                    <button class="save-btn">delete</button>
                                    <a id="cancel-btn-wrapper" href="profile.php">
                                        <button class="cancel" type="button">Cancel</button>
                                    </a>
                                </div>
                                
                            </form>            
                        </div>
                </div>
            <?php } else { ?>
            
                <h2 class="userProfile-title"> User Profile </h2>
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
                                <form id="profile-form" enctype="multipart/form-data" method="post" >

                                    <span class="image-upload">image: <input type="file" name="image"></span><br>
                                    <textarea name="post" cols="40" rows="6"></textarea><br>

                                    <button class="post-btn">Post</button>   
                                </form>
                            </div><?php echo "<span class='profile-error'></span>";?>
                        <hr>
                    </div>

                    <div>
                        <?php

                        $cc = new users();

                        $retriveData = $cc->retrievePosts();

                        if ($retriveData) {

                            $user_row = $cc->displayPost();

                            if (is_array($user_row) && count($user_row) > 0) {

                                foreach ($user_row as $row):?>

                            <div class="display-post">

                                <div class="user-info">
                                    <img src="<?php echo $_SESSION['info']['image'] ?>" id="profile-post">
                                </div>

                                <div class="post">
                                    
                                    <div class="single-post">
                                        <div class="username-date-top">
                                            <span class="profile-name"><?php echo $_SESSION['info']['username'] ?></span>
                                            <span class="date"><?php echo date("jS M, Y",strtotime($row['date'])) ?></span> 
                                        </div>
                                            <span class="reply-wrapper">
                                                <?php echo $row['post'];?>
                                                <label class="toggle-reply" data-id="<?php echo $row['id']; ?>">
                                                    <img src="images/plus-icon.svg" alt="">
                                                </label>
                                            </span>

                                            <div class="btns-left">
                                                <a href="profile.php?action=post_edit&id=<?php echo $row['id'];?>">
                                                    <img class="icon" src="images/edit-icon.svg" alt="edit-icon">
                                                </a>
                                                <a href="profile.php?action=post_delete&id=<?php echo $row['id'];?>">
                                                    <img class="icon" src="images/delete-icon.svg" alt="delete-icon">
                                                </a>
                                            </div>

                                            <?php if (!empty($row['image']) && file_exists($row['image'])):?>
                                                <div>
                                                    <img src="<?php echo $row['image'] ?>" id="post-image">
                                                </div>  
                                            <?php endif;?>

                                            
                                            
                                    </div>

                                    
                                </div>

                            </div>

                            <form id="post-reply-<?php echo $row['id']; ?>" method="post" class="reply-form">
                                <div class="post-reply" id="post-reply-<?php echo $row['id']; ?>">
                                    <textarea name="reply_post" cols="5" rows="4"></textarea><br>
                                    <input type="hidden" name="action" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="post-btn">Reply</button>
                                </div>
                            </form>


                            <?php
                                
                                $catchReply = $cc->getRepliesForPost($row['id']);

                                    if ($catchReply) {
                                        foreach ($catchReply as $replyRow):
                                ?>
                                            <div class="parent-reply-wrapper">
                                                <span class="replied-wrapper index-container">
                                                    <?php echo $replyRow['reply']; ?>
                                                </span>
                                                <div class="btns-left btns-reply-left">
                                                <a href="profile.php?action=post_editReply&id=<?php echo $replyRow['id'];?>">
                                                    <img class="icon" src="images/edit-icon.svg" alt="edit-icon">
                                                </a>
                                                <a href="profile.php?action=post_deleteReply&id=<?php echo $replyRow['id'];?>">
                                                    <img class="icon" src="images/delete-icon.svg" alt="delete-icon">
                                                </a>
                                            </div>
                                            </div>
                                <?php
                                        endforeach;
                                    }?>
                                
                        <?php endforeach; }     
                    }?>


                </div>  

                </div>
            <?php } ?>
    </div>
      
    <footer>
        <h3> Copyright &copy; 2024 Abas Bashir</h3>
    </footer>
   <script src="app.js"></script>
</body>
</html>