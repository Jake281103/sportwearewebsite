<?php


require_once "dbconnect.php";
$userid = '';
$alltotal = 0;

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

function getcartproduct($cid)
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM cart WHERE user_id=? ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cid]);
        $cartproducts = $stmt->fetchAll();
        return $cartproducts;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getcategoryname($id){
    try {
        $conn = connect();
        $sql = "SELECT * FROM category WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $category = $stmt->fetch();
        return $category;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getminiamount($id){
    try {
        $conn = connect();
        $sql = "SELECT MIN(quantity) as min_amount FROM stock WHERE products_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $min_amount = $stmt->fetch();
        return $min_amount;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['deletaction'])) {
    $cid = $_GET['cid'];

    try {
        $conn = connect();
        $sql = "DELETE FROM cart WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$cid]);
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}

$cartproducts = getcartproduct($userid);

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['updatecard'])) {
    foreach($cartproducts as $cartproduct){
        $cartid = $cartproduct['id'];
        $updatequantity = $_POST[$cartid];
        $price = $_POST['price'];
        $totalprice = $updatequantity * $price;
        try {
            $conn = connect();
            $sql = "UPDATE cart SET count = ?, totalprice = ? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$updatequantity,$totalprice,$cartid]);
            $conn = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    $cartproducts = getcartproduct($userid);
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sixty9 | Product Detail Page</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="540x15" href="assets/images/img/logo1.png">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- fontawesome Css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!-- Main CSS File -->
    <link rel="stylesheet" href="./assets/css/cart.css">
</head>

<body>

    <div class="container mt-5 p-3 rounded cart">
        <div class="row no-gutters">
            <div class="col-md-8">
                <div class="product-details mr-2">
                    <div class="d-flex flex-row align-items-center"><a href="./product.php"><i class="fa fa-long-arrow-left"></i><span
                    class="ml-2">Continue Shopping</span></a></div>
                    <hr>
                    <h6 class="mb-0">Shopping cart</h6>

                    <form action="./cart.php" method="POST">
                        <?php if ($cartproducts != null) { ?>

                            <?php foreach ($cartproducts as $cartproduct) { ?>

                                <?php
                                    $alltotal += $cartproduct['totalprice'];
                                    try {
                                        $pid = $cartproduct['products_id'];
                                        $conn = connect();
                                        $sql = "SELECT * from products WHERE id = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->execute([$pid]);
                                        $product = $stmt->fetch();
                                        $conn = null;
                                    } catch (PDOException $e) {
                                        echo $e->getMessage();
                                    }
                                ?>

                                <div class="d-flex justify-content-between align-items-center mt-3 p-3 items rounded">
                                    <div class="d-flex flex-row">
                                        <img class="rounded" src="<?php echo $product['url'] ?>" width="40">
                                        <div class="ml-2"><span class="font-weight-bold d-block"><?php echo $product['name'] ?></span><span
                                                class="spec"><?php echo ucwords(getcategoryname($product['category_id'])['name']) ?></span></div>
                                    </div>
                                    <div class="d-flex flex-row align-items-center">
                                        <input type="number" name="<?php echo $cartproduct['id']?>" class="form-control border-dark rounded-0" value="<?php echo $cartproduct['count'] ?>" min="1" max="<?php echo getminiamount($product['id'])['min_amount'] ?>" step="1" required>
                                        <input type="hidden" name="price" value="<?php echo $product['price'] ?>">
                                        <span class="d-block ml-5 font-weight-bold">$<?php echo $cartproduct['totalprice'] ?></span>
                                        <a href="./cart.php?deletaction=delete&cid=<?php echo $cartproduct['id'] ?>"><span><i class="fa fa-trash-o ml-3 text-black-50"></i></span></a>
                                    </div>
                                </div>
                                
                            <?php } ?>
                            <div class="d-flex justify-content-end mt-4 mb-4">
                                <button class="text-center h6 border-dark reload-btns px-3 py-2" type="submit" name="updatecard">UPDATE CARD</button>
                            </div>

                        <?php }else{ ?>
                            <div class="d-flex justify-content-center align-items-center mt-3 p-5 items rounded shadow">
                                Product Not Found In Cart
                            </div>
                        <?php } ?>
                    </form>
                    
                </div>
            </div>
            <div class="col-md-4">
                <form action="./order.php" method="POST">
                    <div class="payment-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Card details 
                                <?php if(isset($_SESSION['payment_fail'])){ 
                                    echo "<span class='text-danger'>(".$_SESSION['payment_fail'].")</span>";
                                    unset($_SESSION['payment_fail']);
                                    } 
                                ?>
                            </span><img
                                class="rounded" src="https://i.imgur.com/WU501C8.jpg" width="30"></div><span
                            class="type d-block mt-3 mb-1">Card type</span><label class="radio"> <input type="radio"
                                name="card" value="payment" checked> <span><img width="30"
                                    src="https://img.icons8.com/color/48/000000/mastercard.png" /></span> </label>

                        <label class="radio"> <input type="radio" name="card" value="payment"> <span><img width="30"
                                    src="https://img.icons8.com/officel/48/000000/visa.png" /></span> </label>

                        <label class="radio"> <input type="radio" name="card" value="payment"> <span><img width="30"
                                    src="https://img.icons8.com/ultraviolet/48/000000/amex.png" /></span> </label>


                        <label class="radio"> <input type="radio" name="card" value="payment"> <span><img width="30"
                                    src="https://img.icons8.com/officel/48/000000/paypal.png" /></span> </label>
                        <div><label class="credit-card-label">Name on card</label><input type="text"
                                class="form-control credit-inputs" name="cardname" placeholder="Name"></div>
                        <div><label class="credit-card-label">Card number</label><input type="text"
                                class="form-control credit-inputs" name="cardnum" minlength="8" maxlength="16" placeholder="0000 0000 0000 0000"></div>
                        <div class="row">
                            <div class="col-md-6"><label class="credit-card-label">Date</label><input type="text"
                                    class="form-control credit-inputs" maxlength="7" name="carddate" placeholder="12/24"></div>
                            <div class="col-md-6"><label class="credit-card-label">CVV</label><input type="text"
                                    class="form-control credit-inputs" name="cvv" maxlength="3" minlength="3" placeholder="342"></div>
                        </div>
                        <hr class="line">
                        <input type="hidden" name="total" value="<?php echo $alltotal ?>"  />
                        <div class="d-flex justify-content-between information"><span class="fw-bold">Subtotal</span><span>$<?php echo number_format( $alltotal, 2 ) ?></span>
                        </div>
                        <?php if($alltotal > 0){ ?>
                            <button class="btn btn-primary btn-block d-flex justify-content-between mt-3" name="checkout" type="submit">
                                <span>$<?php echo number_format( $alltotal, 2 ) ?></span><span>Checkout<i class="fa fa-long-arrow-right ml-1"></i></span>
                            </button>
                        <?php }else{ ?>
                            <button class="btn btn-primary btn-block d-flex justify-content-between mt-3" type="button" disabled>
                                <span>$<?php echo number_format( $alltotal, 2 ) ?></span><span>Checkout<i class="fa fa-long-arrow-right ml-1"></i></span>
                            </button>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!-- Puygin jQuery js file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</body>

</html>