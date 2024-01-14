<?php 
   require_once "CRUD-functions.php";
    $cc = new users();
    $cc->check_login(); 

   $originalPostID = '';

   if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_GET['action']) && $_GET['action'] == "post_deleteReply")
   {
    
            $cc = new users();

            $deletePost = $cc->deleteReply();

            if ($deletePost) {
                header("Location: index.php");
                die;
            }else{
                    echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        document.querySelector('.profile-error').style.display = 'block';
                        document.querySelector('.profile-error').innerHTML = '$deletePost';
                    });
                </script>";
            }

          
   } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_GET['action']) && $_GET['action'] == "post_editReply")
   {

        $cc = new users();
        $updatePost = $cc->editReply();

        if ($updatePost) {
            header("Location: index.php");
            die;
        }else{
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.profile-error').style.display = 'block';
                    document.querySelector('.profile-error').innerHTML = '$updatePost';
                });
            </script>";
        }

   
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['action']) && !empty($_POST['reply_post'])){

        $originalPostID = $_POST['action'];

        $reply = $_POST['reply_post'];


        $cc = new users();

        $replyAnswer = $cc->addReply($reply, $originalPostID, $_SESSION['info']['id']);

        if ($replyAnswer) {
            header("Location: index.php");
            die;
        }else{
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.profile-error').style.display = 'block';
                    document.querySelector('.profile-error').innerHTML = '$replyAnswer';
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
    <title>Document</title>
</head>
<body>

    <?php include_once 'header.php' ?>

    <div class="main">
        <h3>Timeline</h3>

        <?php
            $cc = new users();
            $allPosts = $cc->latestTrends();
            

            foreach ($allPosts as $row) {

                $user_id = $row['user_id'];
                $readUser = "select username, image from users where id = '$user_id' limit 1";
                $connection = $cc->getConn();
                $stmt = $connection->prepare($readUser);
                $stmt->execute();

                $user_row = $stmt->fetch(PDO::FETCH_ASSOC);

                ?>

            <div class="display-post">

                <div class="user-info">

                    <img src="<?php echo $user_row['image'] ?>" id="profile-post">
                </div>

                
                <div class="post">
                    
                    <div class="single-post">
                            <div class="username-date-top">
                                <span class="profile-name"> <?php echo $user_row['username'] ?> </span>
                                <span class="date"><?php echo date("jS M, Y",strtotime($row['date'])) ?></span> 
                            </div>
                            <span class="reply-wrapper">
                                <?php echo $row['post'];?>
                                <label class="toggle-reply" data-id="<?php echo $row['id']; ?>">
                                    <img src="images/plus-icon.svg" alt="">
                                </label>
                            </span>
                                
                                <?php if (!empty($_SESSION['info']['id'] && $_SESSION['info']['id'] == $user_id)) {?>

                                    <div class="btns-left trend-icon-wrapper">
                                        <a href="profile.php?action=post_edit&id=<?php echo $row['id'];?>&main=trending">
                                            <img class="icon" src="images/edit-icon.svg" alt="edit-icon">
                                        </a>
                                        <a href="profile.php?action=post_delete&id=<?php echo $row['id'];?>">
                                            <img class="icon" src="images/delete-icon.svg" alt="delete-icon">
                                        </a>
                                    </div>
                                <?php } ?>

                                <?php if (file_exists($row['image'])):?>

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
                                $isCurrentUserReply = ($replyRow['user_id'] == $_SESSION['info']['id']);
                                $replyClass = ($isCurrentUserReply) ? 'user-reply' : 'other-reply';
                    ?>          
                                <div class="parent-reply-wrapper <?php echo $replyClass; ?> index-page">
                                    <span class="replied-wrapper index-wrapper">
                                        <?php echo $replyRow['reply']; ?>
                                    </span>

                                    <?php 
                                  
                                    ?>

                                    <?php if (!empty($_SESSION['info']['id'] && $_SESSION['info']['id'] == $replyRow['user_id']) ) {?>
                                        <div class="btns-left btns-reply-left index">
                                        <a href="profile.php?action=post_editReply&id=<?php echo $replyRow['id'];?>&main=trending">
                                            <img class="icon" src="images/edit-icon.svg" alt="edit-icon">
                                        </a>
                                        <a href="profile.php?action=post_deleteReply&id=<?php echo $replyRow['id'];?>">
                                            <img class="icon" src="images/delete-icon.svg" alt="delete-icon">
                                        </a>
                                    </div>
                                <?php } ?>
                                </div>
                    <?php
                            endforeach;
                        }?>
                                
            <?php } ?>
            
        </div>
            
            
    </div>

    
    <footer>
        <h3> Copyright &copy; 2024 Abas Bashir</h3>
    </footer>
    
    <script src="app.js"></script>
</body>
</html>


