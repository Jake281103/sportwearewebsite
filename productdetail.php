<?php 

require_once "dbconnect.php";
$userid = '';
$product = '';

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
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

function getproduct($id){
    try {
        $conn = connect();
        $sql = "SELECT * FROM products WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $product = $stmt->fetchAll();
        return $product;
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

function getstock($id){
    try {
        $conn = connect();
        $sql = "SELECT * FROM stock WHERE products_id=? ORDER BY size_id ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $sizes;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getsizename($id){
    try {
        $conn = connect();
        $sql = "SELECT * FROM size WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        $size = $stmt->fetch();
        return $size;
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

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['pid'])) {
    $_SESSION['id'] = $_GET['pid'];
}

if($_SESSION['email']){
    $userid = getuser($_SESSION['email'])['id'];
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['reviewadd'])) {
    $userid = $_POST['userid'];
    $pid = $_POST['pid'];
    $title = $_POST['title'];
    $content = $_POST['review'];
    $rating = $_POST['rating'];

    try {


        $date = new DateTimeImmutable();
        $datetime = $date->format('Y-m-d');
        // echo $datetime;
        $conn = connect();

        //Get Total rating count From product review
        $sql = "SELECT COUNT(*) AS rate_count FROM productreview WHERE products_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pid]);
        $counts = $stmt->fetch();
        $count = $counts['rate_count'] + 1;
        // $_SESSION['count'] = $count;

        //Get Total rating From
        $sql = "SELECT SUM(rating) AS total_rating FROM productreview WHERE products_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$pid]);
        $totalratings = $stmt->fetch();
        if ($stmt->rowCount() > 0) {
            $recentrating = $totalratings['total_rating'];
            $newrating = $rating;
            settype($recentrating, "integer");
            settype($newrating, "integer");
            $totalrating = $recentrating  + $newrating;
            // $_SESSION['total'] = $totalrating;
            // $_SESSION['array'] = $totalratings;
        } else {
            $totalrating = $rating;
            settype($totalrating, "integer");
            // $_SESSION['total'] = $totalrating;
        }

        $actualrating = $totalrating / $count;
        // $_SESSION['actual'] = $actualrating;


        $stmt = $conn->prepare("INSERT INTO productreview (products_id,user_id,title,content,rating) VALUES (:pid, :cid, :rtitle, :content, :rating)");
        $stmt->bindValue(':pid', $pid);
        $stmt->bindValue(':cid', $userid);
        $stmt->bindValue(':rtitle', $title);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':rating', $rating);
        $stmt->execute();
        $conn = null;
        header("Location: productdetail.php");
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}

$product = getproduct($_SESSION['id']);
$stocks = getstock($_SESSION['id']);

// var_dump($product);
// echo $userid['id'];


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sixty9 | Product Detail Page</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="540x15" href="assets/images/img/logo1.png">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/css/bootstrap.min.css">
    <!-- fontawesome Css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/productdetail.css">
</head>

<body>

    <div class="container mt-5 mb-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6 d-flex justify-content-center align-items-center py-4">
                            <div class="images">
                                <img id="main-image"
                                    src="<?php echo $product[0]['url'] ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="product p-5">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">  
                                        <a href="product.php" class="nav-link text-dark"><i class="fa fa-long-arrow-left"></i> <span class="ml-1">Back</span></a>
                                    </div> 
                                    <?php  if($userid != null){ ?>
                                        <a href="./cart.php" class="nav-link text-dark"><i class="fa fa-shopping-cart text-muted"></i></a>
                                    <?php } ?>
                                </div>
                                <div class="mt-4 mb-3"> <span class="text-uppercase text-muted brand"><?php echo getcategoryname($product[0]['category_id'])['name'] ?></span>
                                    <h5 class="text-uppercase"><?php echo $product[0]['name'] ?></h5>
                                    <div class="price d-flex flex-row align-items-center"> <span
                                            class="act-price">$<?php echo $product[0]['price'] ?></span>
                                    </div>
                                </div>
                                <p class="about">
                                    <?php echo $product[0]['description'] ?>
                                </p>
                                <form action="./addcart.php" method="POST">
                                    <div class="sizes mt-5">
                                        <input type="hidden" name="id" value="<?php echo $_SESSION['id'] ?>"/>
                                        <input type="hidden" name="price" value="<?php echo $product[0]['price'] ?>"/>
                                        <?php if($stocks != null){ ?>
                                            <h6 class="text-uppercase text-dark">Size</h6>
                                            <?php foreach($stocks as $stock){ ?>
                                                <label class="radio">
                                                    <input type="radio" name="size" value="<?php echo $stock['size_id'] ?>" required> <span><?php echo getsizename($stock['size_id'])['name'] ?></span>
                                                </label>
                                            <?php } ?>
                                        <?php } else{ ?>
                                            <h4>Out of stock</h4>
                                        <?php } ?>
                                        <div class="mt-4 text-uppercase fw-bold">
                                            <div style="display: inline-block; padding-right: 10px;" class="">
                                                <label for="quantity">Quantity </label>
                                            </div>
                                            <input type="number" name="quantity" min="0" max="<?php echo getminiamount($_SESSION['id'])['min_amount'] - 1 ?>" id="quantity" required/>
                                        </div>
                                    </div>
                                    <div class="cart mt-4 align-items-center"> 
                                        <?php if($userid != null && $stocks != null){ ?>
                                            <button class="btn btn-danger text-uppercase px-4" type="submit" name="addtocart">Add to Cart</button> 
                                        <?php }else{ ?>
                                            <button class="btn btn-danger text-uppercase px-4" type="submit" name="addtocart" disabled>Add to Cart</button> 
                                        <?php }?>

                                        <?php if($userid != null){ ?>
                                            <button class="btn btn-danger text-uppercase mr-2 px-4" data-toggle="modal" data-target="#exampleModal">Feedback <i class="fa fa-share-alt text-muted ms-3"></i></button>
                                        <?php }else{ ?>
                                            <button class="btn btn-danger text-uppercase mr-2 px-4" data-toggle="modal" data-target="#exampleModal" disabled>Feedback <i class="fa fa-share-alt text-muted ms-3"></i></button>
                                        <?php }?>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Product Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./productdetail.php" method="post">
                        <input type="hidden" name="userid" value="<?php echo $userid['id'] ?>"/>
                        <input type="hidden" name="pid" value="<?php echo $product[0]['id'] ?>"/>
                        <div class="form-group">
                            <label for="title" class="col-form-label">Title:</label>
                            <input type="text" name="title" class="form-control" id="title">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Review:</label>
                            <textarea name="review" id="message-text" class="form-control" rows="4" cols="80" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="rating"><i class="fas fa-award"></i> Rating:</label>
                            <div>
                                <input type="radio" name="rating" id="rating1" class="form-check-input mx-3" value="5" required />
                                <label for="rating1" class="form-check-label">
                                    <i class="fa fa-star text-warning fs-3"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="rating" id="rating2" class="form-check-input mx-3" value="4" required />
                                <label for="rating2" class="form-check-label">
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="rating" id="rating3" class="form-check-input mx-3" value="3" required />
                                <label for="rating3" class="form-check-label">
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="rating" id="rating4" class="form-check-input mx-3" value="2" required />
                                <label for="rating4" class="form-check-label">
                                    <i class="fa fa-star text-warning"></i>
                                    <i class="fa fa-star text-warning"></i>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="rating" id="rating5" class="form-check-input mx-3" value="2" required />
                                <label for="rating5" class="form-check-label">
                                    <i class="fa fa-star text-warning"></i>
                                </label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary mx-3" data-dismiss="modal">Close</button>
                            <?php if($userid != null){ ?>
                                <button type="submit" name="reviewadd" class="btn btn-primary">Send</button>
                            <?php }else{ ?>
                                <button type="submit" name="reviewadd" class="btn btn-primary" disabled>Send</button>
                            <?php }?>
                        </div>
                    </form>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send</button>
                </div> -->
            </div>
        </div>
    </div>


    <!-- Plugin Bootstrap js file -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Puygin jQuery js file -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    

</body>

</html>