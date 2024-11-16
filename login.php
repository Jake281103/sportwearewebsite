<?php

require_once "dbconnect.php";

$errormessage = '';

if (!isset($_SESSION)) {
    session_start(); // to create succes if not exist
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sixty9 | Login Singup Page</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="540x15" href="assets/images/img/logo1.png">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<!-- fontawesome Css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/toast.css">
</head>

<body style="height: 100vh;display: flex; justify-content: center; align-items: center; overflow: hidden;">
        
    <main class="main">
        <div class="login-page bg-image pt-9 pb-8" style="background-image: url('assets/images/img/loginbg.jpg')">
        	<div class="container">
        		<div class="form-box border border-4 border-white" style="background: transparent; backdrop-filter: blur(10px); border-radius: 5px; border-width: 5px;">
        			<div class="form-tab">
	        			<ul class="nav nav-pills nav-fill" role="tablist">
						    <li class="nav-item">
						        <a class="nav-link text-white active" id="signin-tab-2" data-toggle="tab" href="#signin-2" role="tab" aria-controls="signin-2" aria-selected="false">Sign In</a>
						    </li>
						    <li class="nav-item">
						        <a class="nav-link text-white" id="register-tab-2" data-toggle="tab" href="#register-2" role="tab" aria-controls="register-2" aria-selected="true">Register</a>
						    </li>
						</ul>
						<div class="tab-content">
						    <div class="tab-pane fade show active" id="signin-2" role="tabpanel" aria-labelledby="signin-tab-2">
						    	<form action="./signin.php" method="POST">
						    		<div class="form-group">
						    			<label for="singin-email-2">Email address *</label>
						    			<input type="text" class="form-control" id="singin-email-2" name="email" placeholder="Enter your email..." required>
						    		</div><!-- End .form-group -->
						    		<div class="form-group">
						    			<label for="singin-password-2">Password *</label>
						    			<input type="password" class="form-control" id="singin-password-2" name="password" placeholder="Enter your password..." required>
						    		</div><!-- End .form-group -->
						    		<div class="form-footer">
						    			<button type="submit" name="login" class="btn border text-white">
		                					<span>LOG IN</span>
		            						<i class="icon-long-arrow-right"></i>
		                				</button>
		                				<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="signin-remember-2">
											<label class="custom-control-label" for="signin-remember-2">Remember Me</label>
										</div><!-- End .custom-checkbox -->
										<a href="javascript:void(0);" title="use your brain" class="forgot-link">Forgot Your Password?</a>
						    		</div><!-- End .form-footer -->
						    	</form>
						    	<div class="form-choice">
							    	<p class="text-center">or sign in with</p>
							    	<div class="row">
							    		<div class="col-sm-6">
							    			<a href="https://www.google.com/" target="_blank" class="btn btn-login btn-g">
							    				<i class="icon-google"></i>
							    				Login With Google
							    			</a>
							    		</div><!-- End .col-6 -->
							    		<div class="col-sm-6">
							    			<a href="https://www.facebook.com/" target="_blank" class="btn btn-login btn-f">
							    				<i class="icon-facebook-f"></i>
							    				Login With Facebook
							    			</a>
							    		</div><!-- End .col-6 -->
							    	</div><!-- End .row -->
						    	</div><!-- End .form-choice -->
						    </div><!-- .End .tab-pane -->
						    <div class="tab-pane fade " id="register-2" role="tabpanel" aria-labelledby="register-tab-2">
						    	<form action="./signup.php" method="post">
                                    <div class="form-group">
						    			<div class="row">
                                            <div class="col-6">
                                                <label for="name">Full Name <span class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-6">
                                                <label for="phonenumber">Phone Number <span class="text-danger">*</span></label>
                                            </div>
                                        </div>
						    			<div class="row">
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name..." required>
                                            </div>
                                            <div class="col-6">
                                                <input type="text" class="form-control" id="phonenumber" maxlength="11" name="phonenumber" placeholder="Enter your number..." required>
                                            </div>
                                        </div>
						    		</div><!-- End .form-group -->
                                    <div class="form-group">
						    			<label for="register-email-2">Email address <span class="text-danger">*</span></label>
						    			<input type="email" class="form-control" id="register-email-2" name="email" placeholder="Enter your email..." required>
						    		</div><!-- End .form-group -->
                                    <div class="form-group">
						    			<label for="address">Address <span class="text-danger">*</span></label>
						    			<input class="form-control" id="address" name="address" placeholder="Enter your address..." required />
						    		</div><!-- End .form-group -->
						    		<div class="form-group">
						    			<label for="register-password-2">Password <span class="text-danger">*</span></label>
						    			<input type="password" class="form-control" id="register-password-2" name="password" placeholder="Enter strong password..." required>
										<?php if(isset($_SESSION['password_not_strong'])){ ?>
											<p class="pt-1" style="font-size:11px; color:red;">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercaseleeter, one number, and one special character.</p>
										<?php } ?>
						    		</div><!-- End .form-group -->
						    		<div class="d-flex justify-content-between">
						    			<button type="submit" name="signup" class="btn btn border text-white">
		                					<span>SIGN UP</span>
		            						<i class="icon-long-arrow-right"></i>
		                				</button>
		                				<div class="custom-control custom-checkbox">
											<input type="checkbox" class="custom-control-input" id="register-policy-2">
											<label class="custom-control-label" for="register-policy-2">I agree to the <a href="javascript:void(0);">privacy policy</a> *</label>
										</div><!-- End .custom-checkbox -->
						    		</div><!-- End .form-footer -->
						    	</form>
						    </div><!-- .End .tab-pane -->
						</div><!-- End .tab-content -->
					</div><!-- End .form-tab -->
        		</div><!-- End .form-box -->
        	</div><!-- End .container -->
        </div><!-- End .login-page section-bg -->
    </main><!-- End .main -->

	<!-- toast start -->
	<?php 
		if(isset($_SESSION['email_exit'])){  
			$errormessage = $_SESSION['email_exit'];
		} elseif(isset($_SESSION['password_not_strong'])){  
			$errormessage = $_SESSION['password_not_strong'];
		} elseif(isset($_SESSION['loginerror'])){  
			$errormessage = $_SESSION['loginerror'];
		}
	?>
	<?php if($errormessage != null) {?>
		<div class="toasts actives">
			<div class="toast-contents">
				<i class="fas fa-times bg-danger check"></i>

				<div class="message">
				<span class="text text-1">Failed</span>
				<span class="text text-2"><?php echo $errormessage ?></span>
				</div>
			</div>
			<i class="fas fa-times closes"></i>

			<div class="progress actives"></div>
		</div>
	<?php 
		unset($_SESSION['email_exit']);
        unset($_SESSION['password_not_strong']);
		unset($_SESSION['loginerror']);
		$errormessage = '';
	} 
	?>
	<!-- end start -->

    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
	<script src="assets/js/toast.js"></script>
</body>

</html>