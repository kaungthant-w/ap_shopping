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

<!-- Main content -->
<div class="content">
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">User Listing</h3>
        </div>
        
        <?php

          if(!empty($_GET['pageno'])) {
            $pageno = $_GET['pageno'];
          } else {
            $pageno = 1;
          }

          $numOfrecs = 5;
          $offset = ($pageno - 1) * $numOfrecs;

          if(empty($_POST['search']) && empty($_COOKIE["search"])) {
            $stmt = $pdo -> prepare("SELECT * FROM users ORDER BY id DESC");
            $stmt -> execute();
            $rawResult = $stmt -> fetchAll();
            $total_pages = ceil(count($rawResult) / $numOfrecs);

            $stmt = $pdo -> prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfrecs");
            $stmt -> execute();
            $result = $stmt -> fetchAll();
          
          }else{
            $searchKey = isset($_POST['search']) ? $_POST["search"] : $_COOKIE['search'];
            $stmt = $pdo -> prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
            // print_r($stmt);exit();
            $stmt -> execute();
            $rawResult = $stmt -> fetchAll();

            $total_pages = ceil(count($rawResult) / $numOfrecs);

            $stmt = $pdo -> prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
            $stmt -> execute();
            $result = $stmt -> fetchAll();
          }

        ?>
        
        <!-- /.card-header -->
        <div class="card-body">
          <a href="add_user.php" class="btn btn-success mb-3">New User</a>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th>user</th>
                <th>Email</th>
                <th>Role</th>
                <th style="width: 40px">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php

                if ($result) {
                  $i = 1;
                  foreach($result as $value ) {
                    if($value["role"] == 1) {
                      $value["role"] = "Admin";
                    } else {
                      $value["role"] = "User";
                    }
              ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo escape($value["name"]); ?></td>
                <td>
                <?php echo escape(substr($value["email"],0,50)); ?>
                </td>
                <td><?php echo $value["role"]; ?></td>
                <td>
                  <div class="btn-group">
                    <a href="edit_user.php?id=<?php echo $value['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="delete_user.php?id=<?php echo $value['id'] ?>"
                    onclick="return confirm('Are you sure you want to delete this item')"
                      class="btn btn-danger">Delete</a>
                  </div>
                </td>
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
            <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
            <li class="page-item <?php if($pageno <= 1){echo "disabled";} ?>">
              <a class="page-link" href="<?php if($pageno <= 1 ){echo "#";}else{echo "?pageno=".($pageno-1);} ?>">Previous</a>
            </li>
            <li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
            <li class="page-item <?php if($pageno >= $total_pages){echo "disabled";} ?>">
              <a class="page-link" href="<?php if($pageno >= $total_pages){echo "#";}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
            </li>
            <li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
          </ul>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
  <!-- /.row -->
</div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
<!-- Control sidebar content goes here -->
<div class="p-3">
  <h5>Title</h5>
  <p>Sidebar content</p>
</div>
</aside>
<!-- /.control-sidebar -->
<?php
include "footer.php";
?>
