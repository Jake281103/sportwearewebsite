<?php

require_once "./../dbconnect.php";

if (!isset($_SESSION)) {
    session_start();
}

function ispasswordstrong($password)
{
    if (strlen($password) < 8) {
        return false;
    } elseif (isstrong($password)) {
        return true;
    } else {
        return false;
    }
}

function isstrong($password)
{
    $digitcount = 0;
    $capitalcount = 0;
    $speccount = 0;
    $lowercount = 0;
    foreach (str_split($password) as $char) {
        if (is_numeric($char)) {
            $digitcount++;
        } elseif (ctype_upper($char)) {
            $capitalcount++;
        } elseif (ctype_lower($char)) {
            $lowercount++;
        } elseif (ctype_punct($char)) {
            $speccount++;
        }
    }

    if ($digitcount >= 1 && $capitalcount >= 1 && $speccount >= 1) {
        return true;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['register'])) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $hashcode = null;

    try {
        $conn = connect();
        $sql = "SELECT * FROM admin WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            // echo "has be registered";
            $_SESSION['admin_email_exist'] = "Your email has already been registered";
            header("Location:register.php");
        } else {
            if (ispasswordstrong($password)) {
                $hascode = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO admin (name,email,password) VALUES(:name,:email,:password)");
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':password', $hascode);
                $stmt->execute();
                $_SESSION['admin_email'] = $email;
                $_SESSION['admin_signup_success'] = "You have successfully registered";
                header("Location:dashboard.php");
            } else {
                $_SESSION['password_not_strong'] = "Your password is not strong enough";
                header("Location:register.php");
            }
        }
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
