<?php
    session_start();
	require "config/common.php";
	require "config/config.php";

    if($_POST) {
        if(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["phone"]) || empty($_POST["password"]) || empty($_POST["address"]) || strlen($_POST["password"]) < 4) {

            if(empty($_POST["name"])) {
                $nameError = "Name is required";
            }
    
            if(empty($_POST["phone"])) {
                $phoneError = "phone is required";
            }
    
            if(empty($_POST["password"])) {
                $passwordError = "password is required";
            }
    
            if(empty($_POST["address"])) {
                $addressError = "address is required";
            }
    
            if(strlen($_POST["password"]) < 4) {
                $passwordError = "password should be 4 characters at least.";
            }
    
        } else {
            $name = $_POST["name"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $address = $_POST["address"];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
            $stmt = $pdo -> prepare("SELECT * FROM users WHERE email = :email");
            $result = $stmt -> execute([':email' => $email]);
            $user = $stmt -> fetch(PDO::FETCH_ASSOC);
            if($user) {
                echo "<script>alert('This email already exits.')</script>";
            } else {
                $stmt = $pdo -> prepare("INSERT INTO users(name, email, phone, address, password)VALUES(:name,:email, :phone, :address, :password)");
    
                $result = $stmt -> execute(
                    array(":name" => $name, ":email" => $email, ":phone" => $phone, ":address"=> $address, ":password" => $password)
                );
    
                if($result) {
                    echo "<script>alert('Registeration\'s Success! You can login now.');window.location.href='login.php';</script>";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>AP Shopping | Register</title>

	<!--CSS ============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<a class="navbar-brand logo_h p-4" href="index.php">AP Shopping</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">

					</div>
				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Login/Register</h1>
					<nav class="d-flex align-items-center">
						<a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="category.html">Login/Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="login_form_inner">
						<h3>Register To Use Our Shopping Site</h3>
						<form class="row login_form" action="register.php" method="post" id="contactForm" novalidate="novalidate">
						<input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">

                        <div class="col-md-12 form-group">
								<input type="text" class="form-control" id="name" name="name" placeholder="name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'name'"
                                style="<?php echo empty($nameError) ? '': 'border: 1px solid red'; ?>">
							</div>

							<div class="col-md-12 form-group">
								<input type="email" class="form-control" id="email" name="email" placeholder="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'email'"
                                style="<?php echo empty($emailError) ? '': 'border: 1px solid red'; ?>">
							</div>	

                            <div class="col-md-12 form-group">
								<input type="password" class="form-control" id="password" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'"
                                style="<?php echo empty($passwordError) ? '': 'border: 1px solid red'; ?>">
                                <p class="text-danger text-left"><?php echo empty($passwordError) ? '': $passwordError; ?></p>
							</div>

                            <div class="col-md-12 form-group">
								<input type="text" class="form-control" id="phone" name="phone" placeholder="phone" onfocus="this.placeholder = ''" onblur="this.placeholder = 'phone'"
                                style="<?php echo empty($phoneError) ? '': 'border: 1px solid red'; ?>">
							</div>	

                            <div class="col-md-12 form-group">
								<textarea name="address" class="form-control"  onfocus="this.placeholder = ''" onblur="this.placeholder = 'address'" cols="30" rows="5"
                                style="<?php echo empty($addressError) ? '': 'border: 1px solid red'; ?>"></textarea>
							</div>	

							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="primary-btn">Register</button>
                                <a href="login.php" class="primary-btn text-white">Login</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class="footer-text m-0">
				Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="#" target="_blank">AP Shopping</a>
				</p>
			</div>
		</div>
	</footer>
	<!-- End footer Area -->


	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
	 crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>