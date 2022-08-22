<?php
session_start();
require("../config/config.php");
require("../config/common.php");

if(empty($_SESSION["user_id"]) && empty($_SESSION['logged_in'])) {
  header("Location:login.php");
}

if($_SESSION["role"] != 1) {
  header("Location:login.php");
}

if(isset($_POST["search"])) {
  setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
  if(empty($_GET["pageno"])) {
    unset($_COOKIE["search"]);
    setcookie('search', null, -1, '/');
  }
}
?>

<?php include "header.php"; ?>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Order Listings</h3>
          </div>

          <?php
            if(!empty($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
            } else {
                $pageno = 1;
            }
            $numOfrecs = 5;
            $offset = ($pageno - 1) * $numOfrecs;

            $rawstmt = $pdo -> prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=".$_GET["id"]);
            $rawstmt -> execute();
            $rawResult = $rawstmt -> fetchAll();

            $total_pages = ceil(count($rawResult) / $numOfrecs);

            $stmt = $pdo -> prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=".$_GET['id']." LIMIT $offset,$numOfrecs");
            $stmt -> execute();
            $result = $stmt -> fetchAll();
            ?>

          <div class="card-body">
            <a href="order_list.php" class="btn btn-default mb-3">Back</a>
          <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>Order Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  if ($result) {
                    $i = 1;
                    foreach($result as $value ) { ?>
                   <?php
                        $pStmt = $pdo -> prepare("SELECT * FROM categories WHERE id=".$value['product_id']);
                        $pStmt -> execute();
                        $pResult = $pStmt -> fetchAll();
                        
                      ?>

                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo escape($pResult[0]['name']); ?></td>
                            <td><?php echo escape($value["quantity"]); ?></td>
                            <td><?php echo escape(date("Y-m-d",strtotime($value["order_date"]))); ?></td>

                        </tr>

                  <?php
                  $i++;
                    }
                  }
                ?>
              </tbody>
            </table>
          </div>
        <!-- /.card-body -->
      </div>
    </div>
  </div>
  </div>
</div>
</div>

<aside class="control-sidebar control-sidebar-dark">
  <div class="p-3">
    <h5>Title</h5>
    <p>Sidebar content</p>
  </div>
</aside>
<?php
include "footer.php";
?>
