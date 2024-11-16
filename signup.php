<?php

require_once "dbconnect.php";

if (!isset($_SESSION)) {
    session_start(); // to create session if not exist
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

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['signup'])) {

    $name = htmlspecialchars($_POST['name']);
    $phonenumber = htmlspecialchars($_POST['phonenumber']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $password = htmlspecialchars($_POST['password']);
    $hascode = null;

    try {
        $conn = connect();
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            // echo "has be registered";
            $_SESSION['email_exit'] = "Your email has already been registered";
            header("Location:login.php");
        } else {
            // echo "new Customer";
            if (ispasswordstrong($password)) {
                $hascode = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO user (name,phonenumber,email,address,password) VALUES(:name, :phonenumber, :email, :address, :password)");
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':phonenumber', $phonenumber);
                $stmt->bindValue(':email', $email);
                $stmt->bindValue(':address', $address);
                $stmt->bindValue(':password', $hascode);
                $stmt->execute();
                $_SESSION['email'] = $email;
                $_SESSION['customersignup_success'] = "You have successfully registered";
                header("Location:homepage.php");
            }else{
                $_SESSION['password_not_strong'] = "Your password is not strong enough";
                header("Location:login.php");
            }
        }
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>
