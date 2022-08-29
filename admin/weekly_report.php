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
            <h3 class="card-title">Weekly Report</h3>
          </div>

          <?php
          $currentDate = date("Y-m-d");
          $fromDate = date("Y-m-d", strtotime($currentDate.'+1 day'));
          $toDate = date("Y-m-d", strtotime($currentDate.'-7 day'));
          // echo $toDate;exit();

            $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date<=:from_date AND order_date>=:todate ORDER BY id DESC");
            $stmt -> execute([':from_date'=>$fromDate, ':todate'=> $toDate]);
            $result = $stmt->fetchAll(); 
            // print_r($result);
            // exit();

          ?>
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>UserID</th>
                  <th>Total Amount</th>
                  <th>Order Date</th>
                </tr>
              </thead>
              <tbody>
              <?php

                  if ($result) {

                    $i = 1;
                    foreach($result as $value ) { ?>
                      <?php
                        $userStmt = $pdo -> prepare("SELECT * FROM users WHERE id=".$value['user_id']);
                        $userStmt -> execute();
                        $userResult = $userStmt -> fetchAll();
                      ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo escape($userResult[0]['name']); ?></td>
                        <td><?php echo escape($value["total_price"]); ?></td>
                        <td><?php echo escape(date("Y-m-d", strtotime($value["order_date"]))); ?></td>
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
        <div class="card-footer clearfix">
          <ul class="pagination pagination-sm m-0 float-right">
            
          </ul>
        </div>
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


<script>
  $(document).ready(function(){
    $(".table").DataTable();
  });
</script>