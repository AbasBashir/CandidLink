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
    }

?>