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
<?php include("header.php"); ?>
    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                    <?php if(!empty($_SESSION['cart'])) { ?>
                        <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $total = 0;
                                foreach($_SESSION["cart"] as $key => $value ) : 
                                    $id = str_replace("id",'',$key);

                                    $stmt = $pdo -> prepare("SELECT * FROM products WHERE id=".$id);
                                    $stmt -> execute();
                                    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
                                    $total += $result["price"] * $qty;
                                ?>
                                    <tr>
                                        <td>
                                            <div class="media">
                                                <div class="d-flex">
                                                    <img src="admin/images/<?php echo escape($result["image"]); ?>" width="100" height="100" class="img-thumbnail" alt="image">
                                                </div>
                                                <div class="media-body ml-0">
                                                    <p><?php echo escape($result["name"]); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <h5><?php echo escape($result["price"]); ?></h5>
                                        </td>
                                        <td>
                                            <div class="product_count">
                                                <input type="text" name="qty" id="sst" maxlength="12" value="<?php echo $qty; ?>" title="Quantity:"
                                                    class="input-text qty" readonly>
                                            </div>
                                        </td>
                                        <td>
                                            <h5><?php echo escape($result["price"] * $qty); ?></h5>
                                        </td>
                                        <td>
                                            <a class="btn btn-danger" href="cart_item_clear.php?pid=<?php echo $result['id'] ?>">Clear</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>

                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5>$2160.00</h5>
                                </td>
                            </tr>
                            <tr class="out_button_area">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="btn btn-default border" href="clearall.php">Clear All</a>
                                        <a class="primary-btn mx-2" href="index.php">Continue Shopping</a>
                                        <a class="btn btn-default" href="sale_order.php">Order checkout</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->
<?php include "footer.php"; ?>
</body>

</html>
