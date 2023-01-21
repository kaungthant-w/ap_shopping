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
            <h3 class="card-title">Category Listings</h3>
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
                $stmt = $pdo -> prepare("SELECT * FROM categories ORDER BY id DESC");
                $stmt -> execute();
                $rawResult = $stmt -> fetchAll();
                $total_pages = ceil(count($rawResult) / $numOfrecs);

                $stmt = $pdo -> prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offset,$numOfrecs");
                $stmt -> execute();
                $result = $stmt -> fetchAll();

            }else{
                
                $searchKey = isset($_POST['search']) ? $_POST["search"] : $_COOKIE['search'];
                $stmt = $pdo -> prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                // print_r($stmt);exit();
                $stmt -> execute();
                $rawResult = $stmt -> fetchAll();

                $total_pages = ceil(count($rawResult) / $numOfrecs);

                $stmt = $pdo -> prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
                $stmt -> execute();
                $result = $stmt -> fetchAll();
            }
            ?>

          <div class="card-body">
            <a href="cat_add.php" class="btn btn-success mb-3">New Category</a>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th style="width: 40px">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php

                  if ($result) {
                    $i = 1;
                    foreach($result as $value ) { ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo escape($value["name"]); ?></td>
                    <td>
                    <?php echo escape(substr($value["description"],0,50)); ?>
                    </td>
                    <td>
                      <div class="btn-group">
                        <a href="cat_edit.php?id=<?php echo $value['id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="cat_delete.php?id=<?php echo $value['id'] ?>"
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
