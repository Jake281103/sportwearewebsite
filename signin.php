<?php

require_once "dbconnect.php";
if(!isset($_SESSION)){
    session_start();
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])){
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    try{
        $conn = connect();
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        $customer = $stmt->fetch();
        if($stmt->rowCount() > 0){
            if(password_verify($password, $customer['password'])){
                $_SESSION['customerlogin_success'] = "You have successfully logged in";
                $_SESSION['email'] = $email;
                header("Location: homepage.php");
            }else{
                $_SESSION['loginerror'] = "Your Password might be incorrect";
                header("Location:login.php");
            }
        }else{
            $_SESSION['loginerror'] = "Your email might be incorrect";
            header("Location:login.php");
        }

        $conn = null;

    }catch(PDOException $e){
        echo $e->getMessage();
    }


}



?>