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

            // Starting the session
            try{
                    $email = $this->validate($_POST['email']);
                    $password = $_POST['password'];

                    $read = "select * from users where email = :email";
                    $stmt = $this->conn->prepare($read);
                    $stmt->bindParam(':email', $email);
                    $execResult = $stmt->execute();

                    if ($execResult) {

                        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                        $hashedPassword = $userData['password'];

                        if ($userData) {

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

            if(empty($_SESSION['info'])){
                
                header("Location: login.php");
                die;
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


            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    
            try {
                    if ($_SESSION['info']['email'] != $email) {
                        throw new Exception("Wrong Email");
                    }

                    if ($image_added == true) {

                        if (file_exists($_SESSION['info']['image'])) {
                
                            // Delete a previous profile image
                            unlink($_SESSION['info']['image']);
                            
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
    }

?>