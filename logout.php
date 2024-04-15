<?php

    require "CRUD-functions.php";

    // This function is used to free up session variables and allows the user to logout. This still has session data but they are empty
    session_unset();

    // This destroy all sessions data
    session_destroy();


    header("Location: login.php");
    exit();
?>