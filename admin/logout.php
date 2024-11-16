<?php

require_once "./../dbconnect.php";

if(!isset($_SESSION)){
    session_start();
}

if($_SESSION){
    $email = $_SESSION['admin_email'];
    session_destroy();
    header("Location: login.php");
}
    
?>