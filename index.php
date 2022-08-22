<?php include('header.php') ?>

<?php
	
	require "config/config.php";

	if(!empty($_GET['pageno'])) {
		$pageno = $_GET['pageno'];
	} else {
		$pageno = 1;
	}
	$numOfrecs = 5;
	$offset = ($pageno - 1) * $numOfrecs;
	if(empty($_POST['search']) && empty($_COOKIE["search"])) {
		$stmt = $pdo -> prepare("SELECT * FROM products ORDER BY id DESC");
		$stmt -> execute();
		$rawResult = $stmt -> fetchAll();
		$total_pages = ceil(count($rawResult) / $numOfrecs);

		$stmt = $pdo -> prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfrecs");
		$stmt -> execute();
		$result = $stmt -> fetchAll();

	}else{
		
		$searchKey = isset($_POST['search']) ? $_POST["search"] : $_COOKIE['search'];
		$stmt = $pdo -> prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
		// print_r($stmt);exit();
		$stmt -> execute();
		$rawResult = $stmt -> fetchAll();

		$total_pages = ceil(count($rawResult) / $numOfrecs);

		$stmt = $pdo -> prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
		$stmt -> execute();
		$result = $stmt -> fetchAll();
	}
?>

<div class="container">
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Browse Categories</div>
					<ul class="main-categories">
						<li class="main-nav-list">

						<?php
							$catStmt = $pdo -> prepare("SELECT * FROM categories ORDER BY id DESC");
							$catStmt -> execute();
							$catResult = $catStmt -> fetchAll();
						?>

						<?php foreach($catResult as $key => $value) {?> 
							<a data-toggle="collapse"><span class="lnr lnr-arrow-right "></span><?php echo escape($value["name"]); ?></a>
						<?php } ?>
						
						</li>
					</ul>
				</div>
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
			<div class="filter-bar d-flex flex-wrap justify-center">
				<div class="pagination text-center">
					<li class="page-item"><a class="page-link pr-5" href="?pageno=1">First</a></li>

					<li class="page-item"><a <?php if($pageno >= $total_pages){echo "disabled";} ?>
					href="<?php if($pageno <= 1 ){echo "#";}else{echo "?pageno=".($pageno-1);} ?>" class="prev-arrow page-link"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a></li>
					
					<li class="page-item"><a href="#" class="active page-link"><?php echo $pageno; ?></a></li>
					
					<li class="page-item">
						<a <?php if($pageno >= $total_pages){echo "disabled";} ?>
						href="<?php if($pageno >= $total_pages){echo "#";}else{echo "?pageno=".($pageno+1);} ?>" class="next-arrow page-link"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					</li>

					<li class="page-item "><a class="page-link pr-5" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
				</div>
			</div>
			<!-- Start Best Seller -->
			<section class="lattest-product-area pb-40 category-list">
				<div class="row">
					<!-- single product -->
					<?php 
						if($result) {
							foreach($result as $key => $value) {?>
								<div class="col-lg-4 col-md-6">
									<div class="single-product w-100 h-100">
										<img class="img-fluid w-100 h-50" src="admin/images/<?php echo escape($value["image"]) ?>" alt="">
										<div class="product-details">
											<h6><?php echo escape($value["name"]); ?></h6>
											<div class="price">
												<h6><?php echo escape($value["price"]); ?></h6>
											</div>
											<div class="prd-bottom">

												<a href="" class="social-info">
													<span class="ti-bag"></span>
													<p class="hover-text">add to bag</p>
												</a>
												<a href="" class="social-info">
													<span class="lnr lnr-move"></span>
													<p class="hover-text">view more</p>
												</a>
											</div>
										</div>
									</div>
								</div>
							<?php 
							}
						}
					?>
				</div>
			</section>
<!-- End Best Seller -->
<?php include('footer.php');?>
