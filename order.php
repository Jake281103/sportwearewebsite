<?php
require_once "dbconnect.php";
$userid = '';

date_default_timezone_set("Asia/Yangon");

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

if (!isset($_SESSION['email'])) {
    header("Location:homepage.php");
}else{
    $userid = getuser($_SESSION['email'])['id'];
}

function getuser($email){
    try {
        $conn = connect();
        $sql = "SELECT * FROM user WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['checkout'])) {
    $total = $_POST['total'];
    $cid = $userid;
    $cardnumber = $_POST['cardnum'];
    $cardname = $_POST['cardname'];
    $cardexp = $_POST['carddate'];
    $cardcvv = $_POST['cvv'];

    try {
        $conn = connect();
        $sql = "SELECT * FROM payment WHERE crnumber=? AND crname=? AND  expdate=? AND ccv=?";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute([$cardnumber, $cardname, $cardexp, $cardcvv]);
        $customer = $stmt->fetch();
        if ($stmt->rowCount() > 0) {

            $sql = "INSERT INTO orders (user_id,totalprice) VALUES (?,?)";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute([$cid, $total]);

            $sql = "SELECT id FROM orders ORDER BY id DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $orders = $stmt->fetch();
            $orderid = $orders['id'];

            $sql = "SELECT * FROM cart WHERE user_id=?";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute([$cid]);
            $products = $stmt->fetchAll();

            foreach ($products as $product) {
                $pid = $product['products_id'];
                echo $pid;
                $size = $product['size_id'];
                $quantity = $product['count'];
                $totalprice = $product['totalprice'];

                $sql = "INSERT INTO orderdetail (orders_id,products_id,quantity,totalprice) VALUES (?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $status = $stmt->execute([$orderid, $pid, $quantity, $totalprice]);

                $sql = "UPDATE stock SET quantity=quantity-? WHERE products_id=? AND size_id=?";
                $stmt = $conn->prepare($sql);
                $status = $stmt->execute([$quantity, $pid, $size]);

            }

            $sql = "DELETE FROM cart WHERE user_id=?";
            $stmt = $conn->prepare($sql);
            $status = $stmt->execute([$cid]);

            $conn = null;

            $_SESSION['payment_success'] = "Your Order is Successful!!";
            header("Location: homepage.php");
        } else {
            $_SESSION['payment_fail'] = "Something Wrong, Check your information again!!";
            header("Location: cart.php");
        }

        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

?>