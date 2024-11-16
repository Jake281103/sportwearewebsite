<?php 


require_once "dbconnect.php";

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

if($_SESSION['email']){
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

function checkincarttable($cid,$pid,$size)
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM cart WHERE user_id=? AND products_id=? AND size_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cid,$pid,$size]);
        $cartproduct = $stmt->fetch();
        return $cartproduct;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addtocart'])) {
    $cid = $userid;
    $pid = $_POST['id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $size = $_POST['size'];

    $cartproduct = checkincarttable($cid, $pid, $size);

    if ($cartproduct == null) {
        $totalprice = $price * $quantity;
        try {
            $conn = connect();
            $sql = "INSERT INTO cart (products_id,user_id,size_id,count,totalprice) VALUES (?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$pid, $cid, $size, $quantity, $totalprice]);
            $conn = null;
            header("Location: productdetail.php?id=$pid");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    } else {
        $quantity += $cartproduct['count'];
        $totalprice = $price * $quantity;
        try {
            $conn = connect();
            $sql = "UPDATE cart SET count=?, totalprice=?  WHERE products_id=? AND user_id=? AND size_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$quantity, $totalprice, $pid, $cid, $size]);
            $conn = null;
            header("Location: productdetail.php?id=$pid");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}





?>