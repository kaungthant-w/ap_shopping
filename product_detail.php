<?php
session_start();
require("config/config.php");

if(empty($_SESSION["user_id"]) && empty($_SESSION['logged_in'])) {
  header("Location:login.php");
}

// if($_SESSION["role"] != 1) {
//   header("Location:login.php");
// }

// if(isset($_POST["search"])) {
//   setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
// } else {
//   if(empty($_GET["pageno"])) {
//     unset($_COOKIE["search"]);
//     setcookie('search', null, -1, '/');
//   }
// }
?>

<?php include('header.php') ?>

<?php 
  $productStmt = $pdo -> prepare("SELECT * FROM products WHERE id=".$_GET['id']);
  $productStmt -> execute();
  $productResult = $productStmt -> fetchAll();

  // print_r($_SESSION["cart"]);

?>

<!--================Single Product Area =================-->
<div class="product_image_area">
  <div class="container">    
    <div class="row s_product_inner">
    <?php
    foreach($productResult as $value) {?>

        <?php
            $catStmt = $pdo -> prepare("SELECT * FROM categories WHERE id=".$value['category_id']);
            $catStmt -> execute();
            $catResult = $catStmt -> fetchAll();
          ?>
          <div class="col-lg-6">
              <div class="s_Product_carousel">
                <div class="single-prd-item">
                  <img class="img-fluid" src="admin/images/<?php echo $value["image"]; ?>" alt="">
                </div>
                <div class="single-prd-item">
                  <img class="img-fluid" src="admin/images/<?php echo $value["image"]; ?>" alt="">
                </div>
                <div class="single-prd-item">
                  <img class="img-fluid" src="admin/images/<?php echo $value["image"]; ?>" alt="">
                </div>
              </div>
          </div>
            <div class="col-lg-5 offset-lg-1">
              <div class="s_product_text">
                <h3><?php echo $value["name"] ?></h3>
                <h2><?php echo $value["price"] ?>$</h2>
                <ul class="list">
                  <li><a class="active" href="#"><span>Category</span> : <?php echo escape($catResult[0]["name"]); ?></a></li>
                  <li><a href="#"><span>Availibility</span> : <?php echo strlen($value['quantity']) > 0 ? "In Stock":"Out of Stock"; ?></a></li>
                </ul>
                <p><?php echo $value["description"]; ?></p>

                <form action="addtocart.php" method="post">
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <input type="hidden" name="id" value="<?php echo escape($value['id']); ?>">
                
                  <div class="product_count mb-2">
                    <label for="qty">Quantity:</label>
                    <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">

                    <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                    class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>

                    <button onclick="var result = document.getElementById('sst'); var sst = result.value; " class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>

                  </div>
                  
                  <div class="text-warning mb-5 ">Avaliable Stocks : <?php echo $value['quantity']; ?> </div>
                  
                  <div class="card_area d-flex align-items-center">
                    <button type="submit" class="btn primary-btn">Add to Cart</button>
                    <a class="primary-btn" href="index.php">Back</a>
                  </div>
                </form>
              </div>
            </div>
    <?php }
  ?>
    </div>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<?php include('footer.php');?>
