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

if($_POST) {

  if(empty($_POST["name"]) || empty($_POST["description"])) {
    if(empty($_POST['name'])) {
        $nameError = "Category Name is required";
    }

    if(empty($_POST["description"])) {
        $desError = "Description is required";
    }
  } else {
    $name = $_POST["name"];
    $description = $_POST["description"];

    $stmt = $pdo -> prepare("INSERT INTO categories(name, description) VAlUES (:name, :description)");

    $result = $stmt->execute(
        array(":name" => $name, ":description" => $description)
    );

    if($result) {
        echo "<script>alert('Cateogry Added');window.location.href='category.php';</script>";
    }
  }
}
?>

<?php include "header.php"; ?>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form action="cat_add.php" method="post" enctype="multipart/form-data">
              <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                <div class="form-group">
                    <label for="name">name</label><span class="text-danger ml-3"><?php echo empty($nameError) ? '': "*".$nameError; ?></span>
                    <input type="text" name="name" id="name" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="description">description</label><span class="text-danger ml-3"><?php echo empty($desError) ? '': "*".$desError; ?></span>
                    <textarea name="description" class="form-control" id="description" cols="10" rows="10"></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-success" name="" value="Submit">
                    <a href="index.php" class="btn btn-warning">Back</a>
                </div>
              </form>
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