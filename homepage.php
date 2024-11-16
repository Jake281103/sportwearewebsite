<?php

require_once "dbconnect.php";
$userid = '';
$messsage = '';

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

if($_SESSION['email']){
    $userid = getuser($_SESSION['email'])['id'];
}

if(isset($_SESSION['customersignup_success'])){
    $message = $_SESSION['customersignup_success'];
} elseif(isset($_SESSION['customerlogin_success'])){
    $message = $_SESSION['customerlogin_success'];
} elseif(isset($_SESSION['payment_success'])){
    $message = $_SESSION['payment_success'];
}

function getlastproducts($count){
    try {
        $conn = connect();
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT $count";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll();
        return $products;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
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

function getcartcount($userid){
    try {
        $conn = connect();
        $sql = "SELECT COUNT(*) AS count FROM cart WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userid]);
        $count = $stmt->fetch();
        return $count;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getcategory()
{
    try {
        $conn = connect();
        $sql = "SELECT * FROM category";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $category = $stmt->fetchAll();
        return $category;
        $conn = null;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function typeproductcount($type){
    try {
        $conn = connect();
        $sql = "SELECT COUNT(*) AS count FROM products WHERE type=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$type]);
        $count = $stmt->fetchAll();
        return $count;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$categorys = getcategory();
$lastproducts = getlastproducts(10);

// var_dump($categorys);

?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Sixty9 | Home Page</title>
        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="540x15" href="./assetsassets/images/img/logo1.png">
        <!-- fontawesome Css -->
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Plugins CSS File -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
        <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">
        <link rel="stylesheet" href="assets/css/plugins/jquery.countdown.css">
        <!-- Main CSS File -->
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/skins/skin-demo-10.css">
        <link rel="stylesheet" href="assets/css/demos/demo-10.css">
        <link rel="stylesheet" href="assets/css/toast.css">
    </head>

    <body>
        <div class="page-wrapper">
            <header class="header header-8">
                <div class="header-top">
                    <div class="container">
                        <div class="header-left">
                            <div class="header-dropdown">
                                <a href="#">USD</a>
                                <div class="header-menu">
                                    <ul>
                                        <li><a href="javascript:void(0);">Eur</a></li>
                                        <li><a href="javascript:void(0);">Usd</a></li>
                                    </ul>
                                </div><!-- End .header-menu -->
                            </div><!-- End .header-dropdown -->

                            <div class="header-dropdown">
                                <a href="javascript:void(0);">Eng</a>
                                <div class="header-menu">
                                    <ul>
                                        <li><a href="javascript:void(0);">English</a></li>
                                        <li><a href="javascript:void(0);">Myanmar</a></li>
                                    </ul>
                                </div><!-- End .header-menu -->
                            </div><!-- End .header-dropdown -->
                        </div><!-- End .header-left -->

                        <div class="header-right">
                            <ul class="top-menu">
                                <li>
                                    <a href="#">Links</a>
                                    <ul>
                                        <li><a href="tel:+959450097721"><i class="icon-phone"></i>Call: +959450097721</a></li>
                                        <li><a href="about.html">About Us</a></li>
                                        <li><a href="contact.html">Contact Us</a></li>
                                        <?php if(isset($_SESSION['email'])){ ?>
                                            <li><a href="./logout.php"><i class="icon-user"></i>Logout</a></li>
                                        <?php }else{ ?>
                                            <li><a href="./login.php"><i class="icon-user"></i>Login</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            </ul><!-- End .top-menu -->
                        </div><!-- End .header-right -->
                    </div><!-- End .container -->
                </div><!-- End .header-top -->

                <div class="header-middle sticky-header">
                    <div class="container">
                        <div class="header-left">
                            <button class="mobile-menu-toggler">
                                <span class="sr-only">Toggle mobile menu</span>
                                <i class="icon-bars"></i>
                            </button>

                            <a href="./homepage.php" class="logo">
                                <img src="assets/images/img/logo1.png" alt="Molla Logo" width="100" >
                            </a>
                        </div><!-- End .header-left -->

                        <div class="header-right">
                            <nav class="main-nav">
                                <ul class="menu sf-arrows">
                                    <li class="megamenu-container active">
                                        <a href="./homepage.php" class="">Home</a>
                                    </li>
                                    <li>
                                        <a href="./about.php" class="">About</a>
                                    </li>
                                    <li>
                                        <a href="./product.php" class="">Products</a>
                                    </li>
                                    <li>
                                        <a href="./contact.php" class="">Contact Us</a>
                                    </li>
                                </ul><!-- End .menu -->
                            </nav><!-- End .main-nav -->

                            <div class="header-search">
                                <a href="#" class="search-toggle" role="button"><i class="icon-search"></i></a>
                                <form action="#" method="get">
                                    <div class="header-search-wrapper">
                                        <label for="q" class="sr-only">Search</label>
                                        <input type="search" class="form-control" name="q" id="q" placeholder="Search in..." required>
                                    </div><!-- End .header-search-wrapper -->
                                </form>
                            </div><!-- End .header-search -->

                            <div class="dropdown cart-dropdown">
                                <?php if(isset($_SESSION['email'])){ ?>
                                    <a href="cart.php" class="dropdown-toggle" role="button">
                                        <i class="icon-shopping-cart"></i>
                                        <span class="cart-count"><?php echo getcartcount($userid)['count'] ?></span>
                                    </a>
                                <?php } else{ ?>
                                    <a href="#" class="dropdown-toggle" role="button">
                                        <i class="icon-shopping-cart"></i>
                                        <span class="cart-count">0</span>
                                    </a>
                                <?php } ?>
                            </div><!-- End .cart-dropdown -->
                        </div><!-- End .header-right -->
                    </div><!-- End .container -->
                </div><!-- End .header-middle -->
            </header><!-- End .header -->

            <main class="main">
                <div class="container">
                    <div class="intro-slider-container slider-container-ratio mb-2">
                        <div class="intro-slider owl-carousel owl-simple owl-light owl-nav-inside" data-toggle="owl" data-owl-options='{"nav": false}'>
                            <div class="intro-slide">
                                <figure class="slide-image">
                                    <picture>
                                        <source media="(max-width: 480px)" srcset="assets/images/demos/demo-10/slider/slide-1-480w.jpg">
                                        <img src="assets/images/demos/demo-10/slider/slide-3.jpg" alt="Image Desc">
                                    </picture>
                                </figure><!-- End .slide-image -->

                                <div class="intro-content">
                                    <h3 class="intro-subtitle">Deals and Promotions</h3><!-- End .h3 intro-subtitle -->
                                    <h1 class="intro-title text-white">Sneakers & Athletic Shoes</h1><!-- End .intro-title -->

                                    <div class="intro-price text-white">from $9.99</div><!-- End .intro-price -->

                                    <a href="./product.php" class="btn btn-white-primary btn-round">
                                        <span>SHOP NOW</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </a>
                                </div><!-- End .intro-content -->
                            </div><!-- End .intro-slide -->

                            <div class="intro-slide">
                                <figure class="slide-image">
                                    <picture>
                                        <source media="(max-width: 480px)" srcset="assets/images/demos/demo-10/slider/slide-2-480w.jpg">
                                        <img src="assets/images/img/banner3.jpg" alt="Image Desc">
                                    </picture>
                                </figure><!-- End .slide-image -->

                                <div class="intro-content">
                                    <h3 class="intro-subtitle">Trending Now</h3><!-- End .h3 intro-subtitle -->
                                    <h1 class="intro-title text-white">This Week's Most Wanted</h1><!-- End .intro-title -->

                                    <div class="intro-price text-white">from $49.99</div><!-- End .intro-price -->

                                    <a href="./product.php" class="btn btn-white-primary btn-round">
                                        <span>SHOP NOW</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </a>
                                </div><!-- End .intro-content -->
                            </div><!-- End .intro-slide -->

                            <div class="intro-slide">
                                <figure class="slide-image">
                                    <picture>
                                        <source media="(max-width: 480px)" srcset="assets/images/demos/demo-10/slider/slide-3-480w.jpg">
                                        <img src="assets/images/img/banner1.jpg" alt="Image Desc">
                                    </picture>
                                </figure><!-- End .slide-image -->

                                <div class="intro-content">
                                    <h3 class="intro-subtitle text-white">Deals and Promotions</h3><!-- End .h3 intro-subtitle -->
                                    <h1 class="intro-title text-white">Can’t-miss Clearance:</h1><!-- End .intro-title -->

                                    <div class="intro-price text-white">starting at 60% off</div><!-- End .intro-price -->

                                    <a href="./product.php" class="btn btn-white-primary btn-round">
                                        <span>SHOP NOW</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </a>
                                </div><!-- End .intro-content -->
                            </div><!-- End .intro-slide -->
                        </div><!-- End .intro-slider owl-carousel owl-simple -->
                        <span class="slider-loader"></span><!-- End .slider-loader -->
                    </div><!-- End .intro-slider-container -->
                </div><!-- End .container -->

                <div class="banner-group">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="banner banner-overlay">
                                            <a href="#">
                                                <img src="assets/images/img/banner4.jpg" alt="Banner">
                                            </a>

                                            <div class="banner-content banner-content-right">
                                                <h4 class="banner-subtitle"><a href="#">New Arrivals</a></h4><!-- End .banner-subtitle -->
                                                <h3 class="banner-title text-white"><a href="#">Adidas Launches <br> New Sportswear</a></h3><!-- End .banner-title -->
                                                <a href="./product.php" class="btn btn-outline-white banner-link btn-round">Discover Now</a>
                                            </div><!-- End .banner-content -->
                                        </div><!-- End .banner -->
                                    </div><!-- End .col-sm-6 -->

                                    <div class="col-sm-6">
                                        <div class="banner banner-overlay banner-overlay-light">
                                            <a href="#">
                                                <img src="assets/images/img/banner5.jpg" alt="Banner">
                                            </a>

                                            <div class="banner-content">
                                                <h4 class="banner-subtitle"><a href="#">Clearance</a></h4><!-- End .banner-subtitle -->
                                                <h3 class="banner-title text-white"><a href="#">Sandals</a></h3><!-- End .banner-title -->
                                                <div class="banner-text text-white"><a href="#">up to 70% off</a></div><!-- End .banner-text -->
                                                <a href="./product.php" class="btn btn-outline-white banner-link btn-round">Shop Now</a>
                                            </div><!-- End .banner-content -->
                                        </div><!-- End .banner -->
                                    </div><!-- End .col-sm-6 -->
                                </div><!-- End .row -->

                                <div class="banner banner-large banner-overlay d-none d-sm-block mt-1">
                                    <a href="#">
                                        <img src="assets/images/img/banner7.jpg" alt="Banner">
                                    </a>

                                    <div class="banner-content">
                                        <h4 class="banner-subtitle text-white"><a href="#">On Sale</a></h4><!-- End .banner-subtitle -->
                                        <h3 class="banner-title text-white"><a href="#">Slip-On Loafers</a></h3><!-- End .banner-title -->
                                        <div class="banner-text text-white"><a href="#">up to 30% off</a></div><!-- End .banner-text -->
                                        <a href="./product.php" class="btn btn-outline-white banner-link btn-round">Shop Now</a>
                                    </div><!-- End .banner-content -->
                                </div><!-- End .banner -->
                            </div><!-- End .col-lg-8 -->

                            <div class="col-lg-4 d-sm-none d-lg-block">
                                <div class="banner banner-middle banner-overlay">
                                    <a href="#">
                                        <img src="assets/images/img/banner8.jpg" alt="Banner">
                                    </a>

                                    <div class="banner-content banner-content-bottom">
                                        <h4 class="banner-subtitle text-white"><a href="#">On Sale</a></h4><!-- End .banner-subtitle -->
                                        <h3 class="banner-title text-white"><a href="#">Amazing <br>Lace Up Boots</a></h3><!-- End .banner-title -->
                                        <div class="banner-text text-white"><a href="#">from $39.00</a></div><!-- End .banner-text -->
                                        <a href="./product.php" class="btn btn-outline-white banner-link btn-round">Discover Now</a>
                                    </div><!-- End .banner-content -->
                                </div><!-- End .banner -->
                            </div><!-- End .col-lg-4 -->
                        </div><!-- End .row -->
                    </div><!-- End .container -->
                </div><!-- End .banner-group -->

                <div class="icon-boxes-container icon-boxes-separator bg-transparent">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="icon-box icon-box-side">
                                    <span class="icon-box-icon text-primary">
                                        <i class="icon-rocket"></i>
                                    </span>

                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title">Free Shipping</h3><!-- End .icon-box-title -->
                                        <p>Orders $50 or more</p>
                                    </div><!-- End .icon-box-content -->
                                </div><!-- End .icon-box -->
                            </div><!-- End .col-sm-6 col-lg-3 -->
                            
                            <div class="col-sm-6 col-lg-3">
                                <div class="icon-box icon-box-side">
                                    <span class="icon-box-icon text-primary">
                                        <i class="icon-rotate-left"></i>
                                    </span>

                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title">Free Returns</h3><!-- End .icon-box-title -->
                                        <p>Within 30 days</p>
                                    </div><!-- End .icon-box-content -->
                                </div><!-- End .icon-box -->
                            </div><!-- End .col-sm-6 col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="icon-box icon-box-side">
                                    <span class="icon-box-icon text-primary">
                                        <i class="icon-info-circle"></i>
                                    </span>

                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title">Get 20% Off 1 Item</h3><!-- End .icon-box-title -->
                                        <p>when you sign up</p>
                                    </div><!-- End .icon-box-content -->
                                </div><!-- End .icon-box -->
                            </div><!-- End .col-sm-6 col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="icon-box icon-box-side">
                                    <span class="icon-box-icon text-primary">
                                        <i class="icon-life-ring"></i>
                                    </span>

                                    <div class="icon-box-content">
                                        <h3 class="icon-box-title">We Support</h3><!-- End .icon-box-title -->
                                        <p>24/7 amazing services</p>
                                    </div><!-- End .icon-box-content -->
                                </div><!-- End .icon-box -->
                            </div><!-- End .col-sm-6 col-lg-3 -->
                        </div><!-- End .row -->
                    </div><!-- End .container -->
                </div><!-- End .icon-boxes-container -->

                <div class="bg-light pt-5 pb-10 mb-3">
                    <div class="container">
                        <div class="heading heading-center mb-3">
                            <h2 class="title-lg">New Arrivals</h2><!-- End .title -->

                            <ul class="nav nav-pills justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="new-all-link" data-toggle="tab" href="#new-all-tab" role="tab" aria-controls="new-all-tab" aria-selected="true">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="new-women-link" data-toggle="tab" href="#new-women-tab" role="tab" aria-controls="new-women-tab" aria-selected="false">Women's</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="new-men-link" data-toggle="tab" href="#new-men-tab" role="tab" aria-controls="new-men-tab" aria-selected="false">Men's</a>
                                </li>
                            </ul>
                        </div><!-- End .heading -->

                        <div class="tab-content tab-content-carousel">
                            <div class="tab-pane tab-pane-shadow p-0 fade show active" id="new-all-tab" role="tabpanel" aria-labelledby="new-all-link">
                                <div class="owl-carousel owl-simple carousel-equal-height" data-toggle="owl" 
                                    data-owl-options='{
                                        "nav": false, 
                                        "dots": true,
                                        "margin": 0,
                                        "loop": false,
                                        "responsive": {
                                            "0": {
                                                "items":2
                                            },
                                            "480": {
                                                "items":2
                                            },
                                            "768": {
                                                "items":3
                                            },
                                            "992": {
                                                "items":4
                                            },
                                            "1200": {
                                                "items":4,
                                                "nav": true
                                            }
                                        }
                                    }'>
                                    <?php foreach($lastproducts as $lastproduct){ ?>
                                        <div class="product product-3 text-center">
                                            <figure class="product-media">
                                                <span class="product-label label-primary">New</span>
                                                <a href="product.html">
                                                    <img src="<?php echo $lastproduct['url'] ?>" alt="Product image" class="product-image">
                                                </a>
                                            </figure><!-- End .product-media -->

                                            <div class="product-body">
                                                <div class="product-cat">
                                                    <a href="javascript:void(0);">
                                                        <?php 
                                                        foreach($categorys as $category){
                                                            if($lastproduct['category_id'] == $category['id']){
                                                                echo ucwords($category['name']);
                                                            }
                                                        }
                                                        ?>
                                                    </a>
                                                </div><!-- End .product-cat -->
                                                <h3 class="product-title"><a href="product.html"><?php echo $lastproduct['name'] ?></a></h3><!-- End .product-title -->
                                                <div class="product-price">
                                                    <span class="new-price">$<?php echo $lastproduct['price'] ?></span>
                                                </div><!-- End .product-price -->
                                            </div><!-- End .product-body -->

                                            <div class="product-footer">

                                                <div class="product-action">
                                                    <a href="./productdetail.php?pid=<?php echo $lastproduct['id'] ?>" class="btn-product"><span>View</span></a>
                                                </div><!-- End .product-action -->
                                            </div><!-- End .product-footer -->
                                        </div><!-- End .product -->
                                    <?php } ?>
                                </div><!-- End .owl-carousel -->
                            </div><!-- .End .tab-pane -->
                            <div class="tab-pane tab-pane-shadow p-0 fade" id="new-women-tab" role="tabpanel" aria-labelledby="new-women-link">
                                <div class="owl-carousel owl-simple carousel-equal-height" data-toggle="owl" 
                                    data-owl-options='{
                                        "nav": false, 
                                        "dots": true,
                                        "margin": 0,
                                        "loop": false,
                                        "responsive": {
                                            "0": {
                                                "items":2
                                            },
                                            "480": {
                                                "items":2
                                            },
                                            "768": {
                                                "items":3
                                            },
                                            "992": {
                                                "items":4
                                            },
                                            "1200": {
                                                "items":4,
                                                "nav": true
                                            }
                                        }
                                    }'>
                                    <?php foreach($lastproducts as $lastproduct){ ?>
                                        <?php if($lastproduct['type'] == "female"){ ?>
                                            <div class="product product-3 text-center">
                                                <figure class="product-media">
                                                    <span class="product-label label-primary">New</span>
                                                    <a href="product.html">
                                                        <img src="<?php echo $lastproduct['url'] ?>" alt="Product image" class="product-image">
                                                    </a>
                                                </figure><!-- End .product-media -->

                                                <div class="product-body">
                                                    <div class="product-cat">
                                                        <a href="javascript:void(0);">
                                                            <?php 
                                                            foreach($categorys as $category){
                                                                if($lastproduct['category_id'] == $category['id']){
                                                                    echo ucwords($category['name']);
                                                                }
                                                            }
                                                            ?>
                                                        </a>
                                                    </div><!-- End .product-cat -->
                                                    <h3 class="product-title"><a href="product.html"><?php echo $lastproduct['name'] ?></a></h3><!-- End .product-title -->
                                                    <div class="product-price">
                                                        <span class="new-price">$<?php echo $lastproduct['price'] ?></span>
                                                    </div><!-- End .product-price -->
                                                </div><!-- End .product-body -->

                                                <div class="product-footer">

                                                    <div class="product-action">
                                                        <a href="./productdetail.php?pid=<?php echo $lastproduct['id'] ?>" class="btn-product"><span>View</span></a>
                                                    </div><!-- End .product-action -->
                                                </div><!-- End .product-footer -->
                                            </div><!-- End .product -->
                                        <?php } ?>
                                    <?php } ?>
                                    </div><!-- End .owl-carousel -->
                                </div><!-- .End .tab-pane -->
                            <div class="tab-pane tab-pane-shadow p-0 fade" id="new-men-tab" role="tabpanel" aria-labelledby="new-men-link">
                                <div class="owl-carousel owl-simple carousel-equal-height" data-toggle="owl" 
                                    data-owl-options='{
                                        "nav": false, 
                                        "dots": true,
                                        "margin": 0,
                                        "loop": false,
                                        "responsive": {
                                            "0": {
                                                "items":2
                                            },
                                            "480": {
                                                "items":2
                                            },
                                            "768": {
                                                "items":3
                                            },
                                            "992": {
                                                "items":4
                                            },
                                            "1200": {
                                                "items":4,
                                                "nav": true
                                            }
                                        }
                                    }'>
                                    <?php foreach($lastproducts as $lastproduct){ ?>
                                        <?php if($lastproduct['type'] == "male"){ ?>
                                            <div class="product product-3 text-center">
                                                <figure class="product-media">
                                                    <span class="product-label label-primary">New</span>
                                                    <a href="product.html">
                                                        <img src="<?php echo $lastproduct['url'] ?>" alt="Product image" class="product-image">
                                                    </a>
                                                </figure><!-- End .product-media -->

                                                <div class="product-body">
                                                    <div class="product-cat">
                                                        <a href="javascript:void(0);">
                                                            <?php 
                                                            foreach($categorys as $category){
                                                                if($lastproduct['category_id'] == $category['id']){
                                                                    echo ucwords($category['name']);
                                                                }
                                                            }
                                                            ?>
                                                        </a>
                                                    </div><!-- End .product-cat -->
                                                    <h3 class="product-title"><a href="product.html"><?php echo $lastproduct['name'] ?></a></h3><!-- End .product-title -->
                                                    <div class="product-price">
                                                        <span class="new-price">$<?php echo $lastproduct['price'] ?></span>
                                                    </div><!-- End .product-price -->
                                                </div><!-- End .product-body -->

                                                <div class="product-footer">

                                                    <div class="product-action">
                                                        <a href="./productdetail.php?pid=<?php echo $lastproduct['id'] ?>" class="btn-product"><span>View</span></a>
                                                    </div><!-- End .product-action -->
                                                </div><!-- End .product-footer -->
                                            </div><!-- End .product -->
                                        <?php } ?>
                                    <?php } ?>
                                </div><!-- End .owl-carousel -->
                            </div><!-- .End .tab-pane -->
                        </div><!-- End .tab-content -->
                    </div><!-- End .container -->
                </div><!-- End .bg-light -->

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-sm-6 col-md-4">
                            <div class="banner banner-cat">
                                <a href="#">
                                    <img src="assets/images/demos/demo-10/banners/banner-5.jpg" alt="Banner">
                                </a>

                                <div class="banner-content banner-content-overlay text-center">
                                    <h3 class="banner-title font-weight-normal">Women's</h3><!-- End .banner-title -->
                                    <h4 class="banner-subtitle"><?php echo typeproductcount('female')[0]['count'] ?> Products</h4><!-- End .banner-subtitle -->
                                    <a href="./product.php" class="banner-link">SHOP NOW</a>
                                </div><!-- End .banner-content -->
                            </div><!-- End .banner -->
                        </div><!-- End .col-md-4 -->

                        <div class="col-sm-6 col-md-4">
                            <div class="banner banner-cat">
                                <a href="#">
                                    <img src="assets/images/demos/demo-10/banners/banner-6.jpg" alt="Banner">
                                </a>

                                <div class="banner-content banner-content-overlay text-center">
                                    <h3 class="banner-title font-weight-normal">Men's</h3><!-- End .banner-title -->
                                    <h4 class="banner-subtitle"><?php echo typeproductcount('male')[0]['count'] ?> Products</h4><!-- End .banner-subtitle -->
                                    <a href="./product.php" class="banner-link">SHOP NOW</a>
                                </div><!-- End .banner-content -->
                            </div><!-- End .banner -->
                        </div><!-- End .col-md-4 -->

                        <div class="col-sm-6 col-md-4">
                            <div class="banner banner-cat">
                                <a href="#">
                                    <img src="assets/images/demos/demo-10/banners/banner-7.jpg" alt="Banner">
                                </a>

                                <div class="banner-content banner-content-overlay text-center">
                                    <h3 class="banner-title font-weight-normal">Kid's</h3><!-- End .banner-title -->
                                    <h4 class="banner-subtitle">48 Products</h4><!-- End .banner-subtitle -->
                                    <a href="./product.php" class="banner-link">SHOP NOW</a>
                                </div><!-- End .banner-content -->
                            </div><!-- End .banner -->
                        </div><!-- End .col-md-4 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->

                <div class="mb-5"></div><!-- End .mb5 -->

                <div class="container">
                    <div class="cta cta-horizontal cta-horizontal-box bg-image mb-4 mb-lg-6" style="background-image: url(assets/images/demos/demo-10/bg-1.jpg);">
                        <div class="row flex-column flex-lg-row align-items-lg-center">
                            <div class="col">
                                <h3 class="cta-title text-primary">New Deals! Start Daily at 12pm e.t.</h3><!-- End .cta-title -->
                                <p class="cta-desc">Get <em class="font-weight-medium">FREE SHIPPING* & 5% rewards</em> on every order with Sixty9 rewards program</p><!-- End .cta-desc -->
                            </div><!-- End .col -->
                            
                            <div class="col-auto">
                                <a href="#" class="btn btn-white-primary btn-round"><span>Add to Cart for $50.00/yr</span><i class="icon-long-arrow-right"></i></a>
                            </div><!-- End .col-auto -->
                        </div><!-- End .row -->
                    </div><!-- End .cta-horizontal -->
                </div><!-- End .container -->
            </main><!-- End .main -->

            <footer class="footer footer-dark">
                <div class="cta bg-image bg-dark pt-4 pb-5 mb-0" style="background-image: url(assets/images/img/banner10.jpg);">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-sm-10 col-md-8 col-lg-6">
                                <div class="cta-heading text-center">
                                    <h3 class="cta-title text-white">Subscribe for Our Newsletter</h3><!-- End .cta-title -->
                                    <p class="cta-desc text-white">and receive <span class="font-weight-normal">$20 coupon</span> for first shopping</p><!-- End .cta-desc -->
                                </div><!-- End .text-center -->
                            
                                <form action="#">
                                    <div class="input-group input-group-round">
                                        <input type="email" class="form-control form-control-white" placeholder="Enter your Email Address" aria-label="Email Adress" required>
                                        <div class="input-group-append">
                                            <button class="btn btn-white" type="submit"><span>Subscribe</span><i class="icon-long-arrow-right"></i></button>
                                        </div><!-- .End .input-group-append -->
                                    </div><!-- .End .input-group -->
                                </form>
                            </div><!-- End .col-sm-10 col-md-8 col-lg-6 -->
                        </div><!-- End .row -->
                    </div><!-- End .container -->
                </div><!-- End .cta -->
                <div class="footer-middle">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="widget widget-about">
                                    <img src="assets/images/img/logo1.png" class="footer-logo" alt="Footer Logo" width="105" height="25">
                                    <p>With nearly 40 years’ experience, Sixty9 proudly supplies with comfortable sportswear, specially designed accessories and quality footwear for better arch support during sports and exercise.</p>

                                    <div class="social-icons">
                                        <a href="javascript:void(0);" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                        <a href="javascript:void(0);" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                        <a href="javascript:void(0);" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                        <a href="javascript:void(0);" class="social-icon" title="Youtube" target="_blank"><i class="icon-youtube"></i></a>
                                        <a href="javascript:void(0);" class="social-icon" title="Pinterest" target="_blank"><i class="icon-pinterest"></i></a>
                                    </div><!-- End .soial-icons -->
                                </div><!-- End .widget about-widget -->
                            </div><!-- End .col-sm-6 col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="widget">
                                    <h4 class="widget-title">Useful Links</h4><!-- End .widget-title -->

                                    <ul class="widget-list">
                                        <li><a href="about.php">About Sixty9</a></li>
                                        <li><a href="product.php">How to shop on Sixty9</a></li>
                                        <li><a href="faq.php">FAQ</a></li>
                                        <li><a href="contact.php">Contact us</a></li>
                                        <li><a href="login.php">Log in</a></li>
                                    </ul><!-- End .widget-list -->
                                </div><!-- End .widget -->
                            </div><!-- End .col-sm-6 col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="widget">
                                    <h4 class="widget-title">Customer Service</h4><!-- End .widget-title -->

                                    <ul class="widget-list">
                                        <li><a href="javascript:void(0);">Payment Methods</a></li>
                                        <li><a href="javascript:void(0);">Money-back guarantee!</a></li>
                                        <li><a href="javascript:void(0);">Returns</a></li>
                                        <li><a href="javascript:void(0);">Shipping</a></li>
                                        <li><a href="javascript:void(0);">Terms and conditions</a></li>
                                        <li><a href="javascript:void(0);">Privacy Policy</a></li>
                                    </ul><!-- End .widget-list -->
                                </div><!-- End .widget -->
                            </div><!-- End .col-sm-6 col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="widget">
                                    <h4 class="widget-title">My Account</h4><!-- End .widget-title -->

                                    <ul class="widget-list">
                                        <li><a href="login.php">Sign In</a></li>
                                        <li><a href="javascript:void(0);">Track My Order</a></li>
                                        <li><a href="javascript:void(0);">Help</a></li>
                                    </ul><!-- End .widget-list -->
                                </div><!-- End .widget -->
                            </div><!-- End .col-sm-6 col-lg-3 -->
                        </div><!-- End .row -->
                    </div><!-- End .container -->
                </div><!-- End .footer-middle -->

                <div class="footer-bottom">
                    <div class="container">
                        <p class="footer-copyright">Copyright © 2024 Sixty Sportswear. All Rights Reserved.</p><!-- End .footer-copyright -->
                        <figure class="footer-payments">
                            <img src="assets/images/payments.png" alt="Payment methods" width="272" height="20">
                        </figure><!-- End .footer-payments -->
                    </div><!-- End .container -->
                </div><!-- End .footer-bottom -->
            </footer><!-- End .footer -->
        </div><!-- End .page-wrapper -->
        <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

        <!-- Mobile Menu -->
        <div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

        <div class="mobile-menu-container">
            <div class="mobile-menu-wrapper">
                <span class="mobile-menu-close"><i class="icon-close"></i></span>

                <form action="#" method="get" class="mobile-search">
                    <label for="mobile-search" class="sr-only">Search</label>
                    <input type="search" class="form-control" name="mobile-search" id="mobile-search" placeholder="Search in..." required>
                    <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
                </form>
                
                <nav class="mobile-nav">
                    <ul class="mobile-menu">
                        <li class="active">
                            <a href="./homepage.php">Home</a>
                        </li>
                        <li>
                            <a href="./about.php">About</a>
                        </li>
                        <li>
                            <a href="./product.php">Products</a>
                        </li>
                        <li>
                            <a href="./contact.php">Contact Us</a>
                        </li>
                    </ul>
                </nav><!-- End .mobile-nav -->

                <div class="social-icons">
                    <a href="#" class="social-icon" target="_blank" title="Facebook"><i class="icon-facebook-f"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="Twitter"><i class="icon-twitter"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="Instagram"><i class="icon-instagram"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="Youtube"><i class="icon-youtube"></i></a>
                </div><!-- End .social-icons -->
            </div><!-- End .mobile-menu-wrapper -->
        </div><!-- End .mobile-menu-container -->

        <?php if($message != null) {?>
            <div class="toasts actives">
                <div class="toast-contents">
                    <i class="fas fa-check check"></i>

                    <div class="message">
                    <span class="text text-1">Success</span>
                    <span class="text text-2"><?php echo $message ?></span>
                    </div>
                </div>
                <i class="fas fa-times closes"></i>

                <div class="progress actives"></div>
            </div>
        <?php 
            unset($_SESSION['customersignup_success']);
            unset($_SESSION['customerlogin_success']);
            unset($_SESSION['payment_success']);
            $message = '';
        } 
        ?>


        <!-- Plugins JS File -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/jquery.hoverIntent.min.js"></script>
        <script src="assets/js/jquery.waypoints.min.js"></script>
        <script src="assets/js/superfish.min.js"></script>
        <script src="assets/js/owl.carousel.min.js"></script>
        <script src="assets/js/bootstrap-input-spinner.js"></script>
        <script src="assets/js/jquery.plugin.min.js"></script>
        <script src="assets/js/jquery.magnific-popup.min.js"></script>
        <script src="assets/js/jquery.countdown.min.js"></script>
        <!-- Main JS File -->
        <script src="assets/js/main.js"></script>
        <script src="assets/js/demos/demo-10.js"></script>
        <script src="assets/js/toast.js"></script>
    </body>

</html>