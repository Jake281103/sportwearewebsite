<?php 

require_once "./../dbconnect.php";

if (!isset($_SESSION)) {
    session_start();
}

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])){
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    try{
        $conn = connect();
        $sql = "SELECT * FROM admin WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        $admin = $stmt->fetch();
        if($stmt->rowCount() > 0){
            if(password_verify($password, $admin['password'])){
                $_SESSION['admin_login_success'] = "You have successfully logged in";
                $_SESSION['admin_email'] = $email;
                header("Location: dashboard.php");
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