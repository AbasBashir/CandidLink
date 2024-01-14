<?php
    //starting session for current user
    session_start();

    class users{

        private $conn;

        function __construct()
        {   

            $servername = "db";  
            $username = "mariadb"; 
            $password = "mariadb"; 
            $dbname = "mariadb"; 
        
            
            try{
                
                $this->conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
                
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
            } catch(PDOException $e){ 
        
                echo "Connection failed: " .  $e->getMessage(); 
            }

        }

        public function getConn() {
            return $this->conn;
        }


        function __destruct(){

                $this->conn = null;
        }

        function validate($input){

            $input = htmlspecialchars($input); 
            $input = trim($input); 
            $input = stripslashes($input); 

            return $input;
        }

        function checkEmail(){
            
            try {

                $email = $this->validate($_POST['email']);

                $read = "select * from users where email = :email";
                $statement = $this->conn->prepare($read);
                $statement->bindParam(':email', $email);
                $execResult = $statement->execute();
                
                if ($execResult) {

                    $userData = $statement->fetch(PDO::FETCH_ASSOC);

                    if ($userData) {
                        
                        return true;

                    } else {
                        return false;
                    }

                }

            } catch (PDOException $th) {

                $th = "Failed to sign up. Please try again later.";
                return $th;

            }
        }


        function create(){

            try {

                $email = $this->validate($_POST['email']);
                $username = $this->validate($_POST['name']);
                $password = $_POST['password'];

                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $this->conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");

                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $hashedPassword);

                $stmt->execute();

                return true;

            } catch (PDOException $e) {

                $e = "Failed to sign up. Please try again later.";
                return $e;

            }
       
        }

        function login(){

            try{
                    $email = $this->validate($_POST['email']);
                    $password = $_POST['password'];

                    $read = "select * from users where email = :email";
                    $stmt = $this->conn->prepare($read);
                    $stmt->bindParam(':email', $email);
                    $execResult = $stmt->execute();

                    if ($execResult) {

                        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($userData) {

                            $hashedPassword = $userData['password'];

                            if (password_verify($password, $hashedPassword)) {

                                $_SESSION['info'] = $userData;
                                echo "<script> window.location.href = 'profile.php'; </script>"; die;

                            } else {
                                throw new Exception("Wrong email or password");
                            }

                        } else {
                            throw new Exception("Wrong email or password");
                        }
                        
                    }

                    throw new Exception("Failed to execute login query");
            } catch (Exception $e) {
                return $e->getMessage();
            } 
        }

        function check_login(){

            if(!isset($_SESSION['info']['id'])){
                
                header("Location: login.php");
                die;
            }
    
        }

        function deleteProfile() {

            try {
            
                $id = $_SESSION['info']['id'];
    
                $query = "delete from users where id = :id limit 1";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $result = $stmt->execute();

                if ($result) {

                    if (file_exists($_SESSION['info']['image'])) {
                    
                        unlink($_SESSION['info']['image']);
                        
                    }

                    return true;
                }else{
                    throw new Exception("Failed to delete profile, Please try again later.");
                }
    
            } catch (Exception $th) {
                
                return $th->getMessage();

            }
        }

        function editProfile(){
            
            $image_added = false;

            $folder = 'uploads/';

            if (!file_exists($folder)) {
                
                mkdir($folder, 0777, true);

            }

            $image = $folder . $_FILES['image']['name'];
            $uploadStatus = move_uploaded_file($_FILES['image']['tmp_name'], $image);

            if ($uploadStatus === true) {
                
                $image_added = true;
                
            }

            $username = $this->validate($_POST['username']);
            $email = $this->validate($_POST['email']);
            $id = $this->validate($_SESSION['info']['id']);
            $password = $_POST['password'];


            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {
                    if ($_SESSION['info']['email'] != $email) {
                        throw new Exception("Wrong Email");
                    }

                    if ($image_added == true) {

                        if (isset($_SESSION['info']['image']) && $_SESSION['info']['image'] !== null) {

                            $file_path = $_SESSION['info']['image'];
                        
                            if (file_exists($file_path)) {
        
                                unlink($_SESSION['info']['image']);

                            } 
                        } 
                        
                        $query = "update users set username = :username, password = :hashedPassword, image = '$image' where id = :id limit 1";
                    }else{
                        $query = "update users set username = :username, password = :hashedPassword where id = :id limit 1";
                    }
    
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':hashedPassword', $hashedPassword);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
    
                    $read = "select * from users where id = :id limit 1";
                    $stmt = $this->conn->prepare($read);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
    
                    // here we are going to fetch the row from the session
                    if ($stmt->rowCount() > 0) {
                    
                        $row =  $stmt->fetch(PDO::FETCH_ASSOC);

                        // update the session with the new information
                        $_SESSION['info'] = $row;

                        return true;
            
                    }else{

                        throw new Exception("Failed to update profile. Please try again later.");
                    }

                    throw new Exception("Failed to execute update profile");
    
                } catch (Exception $th) {
        
                    return $th->getMessage();
                }
        }


        function addingPost(){
            
            $image = '';

            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
                
                
                $folder = 'uploads/';
    
    
                if (!file_exists($folder)) {
                    
                    mkdir($folder, 0777, true);
    
                }
    
             
                $image = $folder . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], $image);
                
    
                $image_added = true;
    
            }
    
            $post = $this->validate($_POST['post']);
            $user_id = $_SESSION['info']['id'];
            $date = date('Y-m-d H:i:s');
    
            try {
                    $query = "insert into posts (user_id,post,image,date) values (:user_id, :post, :image, :date) ";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(':user_id', $user_id);
                    $stmt->bindParam(':post', $post);
                    $stmt->bindParam(':image', $image);
                    $stmt->bindParam(':date', $date);

                    $stmt->execute();

            } catch (Exception $th) {
    
                return $th->getMessage();
            }
        }

        function retrievePosts(){

            try {
                $id = $_SESSION['info']['id'];

                $read = "select * from posts where user_id = '$id' order by id desc limit 10";
                $stmt = $this->conn->prepare($read);
                $result = $stmt->execute();

                if ($result) {
                   return true;
                }

                return false;

            } catch (Exception $th) {
                return $th->getMessage();
            }

        }

        function displayPost(){

            try {
                        
                $id = $_SESSION['info']['id'];

                $read = "select * from posts where user_id = '$id' order by id desc";
                $stmt = $this->conn->prepare($read);
                $stmt->execute();



                if ($stmt->rowCount() > 0){

                    $result = $stmt->fetchALL(PDO::FETCH_ASSOC);

                    return $result;
                }

            } catch (Exception $th) {
                return $th->getMessage();
                
            }



        }

        function deleteReply(){

            try {

                //Edit a post  
                $id = $_GET['id'];
                $user_id = $_SESSION['info']['id'];

                $query = "delete from replies where id = :id && user_id = :user_id limit 1";

                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->execute();

                return true;

            } catch (Exception $th) {
                
                return $th->getMessage();
            }

        }

        function editReply(){

            try {


                //Edit a post  
                $id = $_GET['id'];
                $user_id = $_SESSION['info']['id'];
  


                $post = $_POST['post'];

                    $query = "update replies set reply = :post where id = :id && user_id = :user_id limit 1";

                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':post', $post);
                $stmt->execute();

                return true;

            } catch (Exception $th) {
                
                return $th->getMessage();
            }
        }

        function editPost(){

            try {

                //Edit a post  
                $id = $_GET['id'];
                $user_id = $_SESSION['info']['id'];

                $image_added = false;

                if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
                    

                    $folder = 'uploads/';


                    if (!file_exists($folder)) {
                        
                        mkdir($folder, 0777, true);

                    }


                    $image = $folder . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], $image);

                    $read = "select * from posts where id = :id && user_id = :user_id limit 1";
                    $stmtRead = $this->conn->prepare($read);
                    $stmtRead->bindParam(':id', $id);
                    $stmtRead->bindParam(':user_id', $user_id);
                    $stmtRead->execute();
                    

                    if ($stmtRead->rowCount() > 0) {
                        
                        $row =  $stmtRead->fetch(PDO::FETCH_ASSOC);

                        if (file_exists($row['image'])) {
                        
                            unlink($row['image']);
                            
                        }
                    
                    }


                    $image_added = true;

                }

                $post = $_POST['post'];

                if ($image_added == true) {
                    $query = "update posts set post = :post,  image = '$image' where id = :id && user_id = :user_id limit 1";
                }else{
                    $query = "update posts set post = :post where id = :id && user_id = :user_id limit 1";
                }


                
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':post', $post);
                $stmt->execute();

                return true;

            } catch (Exception $th) {
                
                return $th->getMessage();
            }
        }


        function deletePost(){

            try {
               
                $id = $_GET['id'];
 
                $user_id = $_SESSION['info']['id'];
 
                 // here we make sure the actual user is the only one who can delete their posts. We do this by using && condition with the current user logged in
                $read = "select * from posts where id = '$id' && user_id = '$user_id' limit 1";
                $stmtRead = $this->conn->prepare($read);
                $stmtRead->execute();
 
                if ($stmtRead->rowCount() > 0) {
                    
                    $row =  $stmtRead->fetch(PDO::FETCH_ASSOC);
 
                    if (!empty($row['image']) && file_exists($row['image'])) {
                    
                        unlink($row['image']);
                        
                    }
                
        
                }
 
                $query = "delete from posts where id = '$id' && user_id = '$user_id' limit 1";
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
 
            } catch (Exception $th) {
                return $th->getMessage();
            }
        }

        function latestTrends(){
            try {
                
                // we remove the where clause as well in order to show posts from all users
                $read = "select * from posts order by id desc limit 10";
                $stmt = $this->conn->prepare($read);
                $stmt->execute();

                if ($stmt->rowCount() > 0){

                    $users = $stmt->fetchALL(PDO::FETCH_ASSOC);
                    
                    return $users;
                }

            } catch (Exception $th) {
                return $th->getMessage();
            }
        }
        
        function getRepliesForPost($originalPostID) {
            try {
                $query = "select * from replies where post_id = :post_id";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':post_id', $originalPostID);
                $stmt->execute();
        
                if ($stmt->rowCount() > 0) {
                    $repliesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $repliesData;
                }
            } catch (Exception $th) {
                return $th->getMessage();
            }
        }

        function addReply($post, $originalPostID, $replier){

            try {
                $query = "insert into replies (post_id, user_id, reply, date) VALUES (:post_id, :user_id, :reply, NOW())";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':post_id', $originalPostID);
                $stmt->bindParam(':user_id', $replier);
                $stmt->bindParam(':reply', $post);
                $stmt->execute();
                
                return true;
                
            } catch (Exception $th) {
                return $th->getMessage();
            }
        }

    }



?>