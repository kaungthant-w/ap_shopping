<?php require "header.php"; ?>
<?php
session_start();
require "config/config.php";

if(!empty($_SESSION["cart"])) {
	$userId = $_SESSION["user_id"];
	$total = 0;

	foreach ($_SESSION["cart"] as $key => $qty) {
		$id = str_replace("id", '', $key);

		$stmt = $pdo -> prepare("SELECT * FROM products WHERE id=".$id);
		$stmt -> execute();
		$result = $stmt -> fetch(PDO::FETCH_ASSOC);
		$total += $result["price"] * $qty;
	}

	// insert into sale_orders table
	$stmt = $pdo -> prepare("INSERT INTO sale_orders(user_id, total_price, order_date)VALUES(:user_id, :total, :odate)");

	$result = $stmt -> execute(
		array(":user_id" => $userId, ":total" => $total, ":odate" => date('Y-m-d H:i:s'))
	);

	if($result) {
		$saleOrderId = $pdo -> lastInsertId();

		//insert into sale_order_detail
		foreach($_SESSION["cart"] as $key => $qty) {
			$id = str_replace('id', '', $key);

			$stmt = $pdo -> prepare("INSERT INTO sale_order_detail(sale_order_id, product_id, quantity)VALUES(:sid, :pid, :qty)");

			$result = $stmt -> execute(
				array(":sid"=>$saleOrderId, ":pid"=>$id, ":qty"=>$qty)
			);

			$qtyStmt = $pdo -> prepare("SELECT quantity FROM products WHERE id=".$id);
			$qtyStmt -> execute();
			$qtyResult = $qtyStmt -> fetch(PDO::FETCH_ASSOC);

			$updateQty = $qtyResult["quantity"] - $qty;
			$stmt = $pdo -> prepare("UPDATE products SET quantity=:qty WHERE id = :pid");

			$result = $stmt -> execute(
				array(":qty"=> $updateQty, ":pid" => $id)
			);
		}

		unset($_SESSION["cart"]);
	}

}

?>


	<!--================Order Details Area =================-->
	<section class="order_details section_gap">
		<div class="container">
			<h3 class="title_confirmation">Thank you. Your order has been received.</h3>

		</div>
	</section>
	<!--================End Order Details Area =================-->

<?php require("footer.php"); ?>
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
