<?php

require_once "dbconnect.php";
$userid = '';
$messsage = '';

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}

$userid = getuser($_SESSION['email'])['id'];

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

?>


<!DOCTYPE html>
<html lang="en">


<!-- molla/about-2.html  22 Nov 2019 10:03:54 GMT -->
<head>
    <meta charset="UTF-8">
    <title>Sixty9 | Home Page</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="540x15" href="./assetsassets/images/img/logo1.png">
    <!-- fontawesome Css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
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
                                <li class="megamenu-container">
                                    <a href="./homepage.php" class="">Home</a>
                                </li>
                                <li class="active">
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
        	<div class="page-header text-center" style="background-image: url('assets/images/page-header-bg.jpg')">
        		<div class="container">
        			<h1 class="page-title">About Us<span>Pages</span></h1>
        		</div><!-- End .container -->
        	</div><!-- End .page-header -->
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="homepage.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">About Us</a></li>
                    </ol>
                </div><!-- End .container -->
            </nav><!-- End .breadcrumb-nav -->

            <div class="page-content pb-3">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
                            <div class="about-text text-center mt-3">
                                <h2 class="title text-center mb-2">Who We Are</h2><!-- End .title text-center mb-2 -->
                                <p>Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, uctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis. </p>
                                <img src="assets/images/about/about-2/signature.png" alt="signature" class="mx-auto mb-5">

                                <img src="assets/images/about/about-2/img-1.jpg" alt="image" class="mx-auto mb-6">
                            </div><!-- End .about-text -->
                        </div><!-- End .col-lg-10 offset-1 -->
                    </div><!-- End .row -->
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-sm-6">
                            <div class="icon-box icon-box-sm text-center">
                                <span class="icon-box-icon">
                                    <i class="icon-puzzle-piece"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">Design Quality</h3><!-- End .icon-box-title -->
                                    <p>Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero <br>eu augue.</p>
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-lg-4 col-sm-6 -->

                        <div class="col-lg-4 col-sm-6">
                            <div class="icon-box icon-box-sm text-center">
                                <span class="icon-box-icon">
                                    <i class="icon-life-ring"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">Professional Support</h3><!-- End .icon-box-title -->
                                    <p>Praesent dapibus, neque id cursus faucibus, <br>tortor neque egestas augue, eu vulputate <br>magna eros eu erat. </p>
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-lg-4 col-sm-6 -->

                        <div class="col-lg-4 col-sm-6">
                            <div class="icon-box icon-box-sm text-center">
                                <span class="icon-box-icon">
                                    <i class="icon-heart-o"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h3 class="icon-box-title">Made With Love</h3><!-- End .icon-box-title -->
                                    <p>Pellentesque a diam sit amet mi ullamcorper <br>vehicula. Nullam quis massa sit amet <br>nibh viverra malesuada.</p> 
                                </div><!-- End .icon-box-content -->
                            </div><!-- End .icon-box -->
                        </div><!-- End .col-lg-4 col-sm-6 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->

                <div class="mb-2"></div><!-- End .mb-2 -->

                <div class="bg-image pt-7 pb-5 pt-md-12 pb-md-9" style="background-image: url(assets/images/backgrounds/bg-4.jpg)">
                    <div class="container">
                        <div class="row">
                            <div class="col-6 col-md-3">
                                <div class="count-container text-center">
                                    <div class="count-wrapper text-white">
                                        <span class="count" data-from="0" data-to="40" data-speed="3000" data-refresh-interval="50">0</span>k+
                                    </div><!-- End .count-wrapper -->
                                    <h3 class="count-title text-white">Happy Customer</h3><!-- End .count-title -->
                                </div><!-- End .count-container -->
                            </div><!-- End .col-6 col-md-3 -->

                            <div class="col-6 col-md-3">
                                <div class="count-container text-center">
                                    <div class="count-wrapper text-white">
                                        <span class="count" data-from="0" data-to="20" data-speed="3000" data-refresh-interval="50">0</span>+
                                    </div><!-- End .count-wrapper -->
                                    <h3 class="count-title text-white">Years in Business</h3><!-- End .count-title -->
                                </div><!-- End .count-container -->
                            </div><!-- End .col-6 col-md-3 -->

                            <div class="col-6 col-md-3">
                                <div class="count-container text-center">
                                    <div class="count-wrapper text-white">
                                        <span class="count" data-from="0" data-to="95" data-speed="3000" data-refresh-interval="50">0</span>%
                                    </div><!-- End .count-wrapper -->
                                    <h3 class="count-title text-white">Return Clients</h3><!-- End .count-title -->
                                </div><!-- End .count-container -->
                            </div><!-- End .col-6 col-md-3 -->

                            <div class="col-6 col-md-3">
                                <div class="count-container text-center">
                                    <div class="count-wrapper text-white">
                                        <span class="count" data-from="0" data-to="15" data-speed="3000" data-refresh-interval="50">0</span>
                                    </div><!-- End .count-wrapper -->
                                    <h3 class="count-title text-white">Awards Won</h3><!-- End .count-title -->
                                </div><!-- End .count-container -->
                            </div><!-- End .col-6 col-md-3 -->
                        </div><!-- End .row -->
                    </div><!-- End .container -->
                </div><!-- End .bg-image pt-8 pb-8 -->

                <div class="bg-light-2 pt-6 pb-7 mb-6">
                    <div class="container">
                        <h2 class="title text-center mb-4">Meet Our Team</h2><!-- End .title text-center mb-2 -->

                        <div class="row">
                            <div class="col-sm-6 col-lg-3">
                                <div class="member member-2 text-center">
                                    <figure class="member-media">
                                        <img src="assets/images/team/about-2/member-1.jpg" alt="member photo">

                                        <figcaption class="member-overlay">
                                            <div class="social-icons social-icons-simple">
                                                <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                                <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                                <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            </div><!-- End .soial-icons -->
                                        </figcaption><!-- End .member-overlay -->
                                    </figure><!-- End .member-media -->
                                    <div class="member-content">
                                        <h3 class="member-title">Samanta Grey<span>Founder & CEO</span></h3><!-- End .member-title -->
                                    </div><!-- End .member-content -->
                                </div><!-- End .member -->
                            </div><!-- End .col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="member member-2 text-center">
                                    <figure class="member-media">
                                        <img src="assets/images/team/about-2/member-2.jpg" alt="member photo">

                                        <figcaption class="member-overlay">
                                            <div class="social-icons social-icons-simple">
                                                <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                                <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                                <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            </div><!-- End .soial-icons -->
                                        </figcaption><!-- End .member-overlay -->
                                    </figure><!-- End .member-media -->
                                    <div class="member-content">
                                        <h3 class="member-title">Bruce Sutton<span>Sales & Marketing Manager</span></h3><!-- End .member-title -->
                                    </div><!-- End .member-content -->
                                </div><!-- End .member -->
                            </div><!-- End .col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="member member-2 text-center">
                                    <figure class="member-media">
                                        <img src="assets/images/team/about-2/member-3.jpg" alt="member photo">

                                        <figcaption class="member-overlay">
                                            <div class="social-icons social-icons-simple">
                                                <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                                <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                                <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            </div><!-- End .soial-icons -->
                                        </figcaption><!-- End .member-overlay -->
                                    </figure><!-- End .member-media -->
                                    <div class="member-content">
                                        <h3 class="member-title">Janet Joy<span>Product Manager</span></h3><!-- End .member-title -->
                                    </div><!-- End .member-content -->
                                </div><!-- End .member -->
                            </div><!-- End .col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="member member-2 text-center">
                                    <figure class="member-media">
                                        <img src="assets/images/team/about-2/member-4.jpg" alt="member photo">

                                        <figcaption class="member-overlay">
                                            <div class="social-icons social-icons-simple">
                                                <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                                <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                                <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            </div><!-- End .soial-icons -->
                                        </figcaption><!-- End .member-overlay -->
                                    </figure><!-- End .member-media -->
                                    <div class="member-content">
                                        <h3 class="member-title">Mark Pocket<span>Product Manager</span></h3><!-- End .member-title -->
                                    </div><!-- End .member-content -->
                                </div><!-- End .member -->
                            </div><!-- End .col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="member member-2 text-center">
                                    <figure class="member-media">
                                        <img src="assets/images/team/about-2/member-5.jpg" alt="member photo">

                                        <figcaption class="member-overlay">
                                            <div class="social-icons social-icons-simple">
                                                <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                                <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                                <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            </div><!-- End .soial-icons -->
                                        </figcaption><!-- End .member-overlay -->
                                    </figure><!-- End .member-media -->
                                    <div class="member-content">
                                        <h3 class="member-title">Damion Blue<span>Sales & Marketing Manager</span></h3><!-- End .member-title -->
                                    </div><!-- End .member-content -->
                                </div><!-- End .member -->
                            </div><!-- End .col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="member member-2 text-center">
                                    <figure class="member-media">
                                        <img src="assets/images/team/about-2/member-6.jpg" alt="member photo">

                                        <figcaption class="member-overlay">
                                            <div class="social-icons social-icons-simple">
                                                <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                                <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                                <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            </div><!-- End .soial-icons -->
                                        </figcaption><!-- End .member-overlay -->
                                    </figure><!-- End .member-media -->
                                    <div class="member-content">
                                        <h3 class="member-title">Lenard Smith<span>Product Manager</span></h3><!-- End .member-title -->
                                    </div><!-- End .member-content -->
                                </div><!-- End .member -->
                            </div><!-- End .col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="member member-2 text-center">
                                    <figure class="member-media">
                                        <img src="assets/images/team/about-2/member-7.jpg" alt="member photo">

                                        <figcaption class="member-overlay">
                                            <div class="social-icons social-icons-simple">
                                                <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                                <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                                <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            </div><!-- End .soial-icons -->
                                        </figcaption><!-- End .member-overlay -->
                                    </figure><!-- End .member-media -->
                                    <div class="member-content">
                                        <h3 class="member-title">Rachel Green<span>Product Manager</span></h3><!-- End .member-title -->
                                    </div><!-- End .member-content -->
                                </div><!-- End .member -->
                            </div><!-- End .col-lg-3 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="member member-2 text-center">
                                    <figure class="member-media">
                                        <img src="assets/images/team/about-2/member-8.jpg" alt="member photo">

                                        <figcaption class="member-overlay">
                                            <div class="social-icons social-icons-simple">
                                                <a href="#" class="social-icon" title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                                                <a href="#" class="social-icon" title="Twitter" target="_blank"><i class="icon-twitter"></i></a>
                                                <a href="#" class="social-icon" title="Instagram" target="_blank"><i class="icon-instagram"></i></a>
                                            </div><!-- End .soial-icons -->
                                        </figcaption><!-- End .member-overlay -->
                                    </figure><!-- End .member-media -->
                                    <div class="member-content">
                                        <h3 class="member-title">David Doe<span>Product Manager</span></h3><!-- End .member-title -->
                                    </div><!-- End .member-content -->
                                </div><!-- End .member -->
                            </div><!-- End .col-lg-3 -->
                        </div><!-- End .row -->

                        <div class="text-center mt-3">
                            <a href="blog.html" class="btn btn-sm btn-minwidth-lg btn-outline-primary-2">
                                <span>LETS START WORK</span>
                                <i class="icon-long-arrow-right"></i>
                            </a>
                        </div><!-- End .text-center -->
                    </div><!-- End .container -->
                </div><!-- End .bg-light-2 pt-6 pb-6 -->

                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
                            <div class="brands-text text-center mx-auto mb-6">
                                <h2 class="title">The world's premium design brands in one destination.</h2><!-- End .title -->
                                <p>Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nis</p>
                            </div><!-- End .brands-text -->
                            <div class="brands-display">
                                <div class="row justify-content-center">
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <a href="#" class="brand">
                                            <img src="assets/images/brands/1.png" alt="Brand Name">
                                        </a>
                                    </div><!-- End .col-md-3 -->

                                    <div class="col-6 col-sm-4 col-md-3">
                                        <a href="#" class="brand">
                                            <img src="assets/images/brands/2.png" alt="Brand Name">
                                        </a>
                                    </div><!-- End .col-md-3 -->

                                    <div class="col-6 col-sm-4 col-md-3">
                                        <a href="#" class="brand">
                                            <img src="assets/images/brands/3.png" alt="Brand Name">
                                        </a>
                                    </div><!-- End .col-md-3 -->

                                    <div class="col-6 col-sm-4 col-md-3">
                                        <a href="#" class="brand">
                                            <img src="assets/images/brands/7.png" alt="Brand Name">
                                        </a>
                                    </div><!-- End .col-md-3 -->

                                    <div class="col-6 col-sm-4 col-md-3">
                                        <a href="#" class="brand">
                                            <img src="assets/images/brands/4.png" alt="Brand Name">
                                        </a>
                                    </div><!-- End .col-md-3 -->

                                    <div class="col-6 col-sm-4 col-md-3">
                                        <a href="#" class="brand">
                                            <img src="assets/images/brands/5.png" alt="Brand Name">
                                        </a>
                                    </div><!-- End .col-md-3 -->

                                    <div class="col-6 col-sm-4 col-md-3">
                                        <a href="#" class="brand">
                                            <img src="assets/images/brands/6.png" alt="Brand Name">
                                        </a>
                                    </div><!-- End .col-md-3 -->

                                    <div class="col-6 col-sm-4 col-md-3">
                                        <a href="#" class="brand">
                                            <img src="assets/images/brands/9.png" alt="Brand Name">
                                        </a>
                                    </div><!-- End .col-md-3 -->
                                </div><!-- End .row -->
                            </div><!-- End .brands-display -->
                        </div><!-- End .col-lg-10 offset-lg-1 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .page-content -->
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

    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/jquery.countTo.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/demos/demo-10.js"></script>
    <script src="assets/js/toast.js"></script>
</body>


<!-- molla/about-2.html  22 Nov 2019 10:04:01 GMT -->
</html>