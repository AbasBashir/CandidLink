<?php

    class ToDoList{

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

    }

?>