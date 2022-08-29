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
        if(empty($_POST["name"])) {
          $nameError = 'name cannot be empty';
        }
        if(empty($_POST["description"])) {
          $desError = "description cannot be empty";
        }
      } else {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $description = $_POST["description"];

          $stmt = $pdo -> prepare("UPDATE categories SET name='$name', description='$description' WHERE id='$id'");
          $result = $stmt -> execute(
            array(":name" => $name, ":description" => $description, ":id" => $id)
          );
          if($result) {
              echo "<script>alert('Successfully Updated.');window.location.href='category.php';</script>";
          }
        } 
    }

    $stmt = $pdo -> prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
    $stmt -> execute();
    $result = $stmt -> fetchAll();
?>


<?php include "header.php"; ?>
<?php 
    if(empty($_SESSION["user_id"]) && empty($_SESSION['logged_in'])) {
      header("Location:login.php");
    }
?>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
                <form action="cat_edit.php" method="post" enctype="multipart/form-data">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                      <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                      <label for="name">name</label><span class="text-danger ml-3"><?php echo empty($nameError) ? '': "*".$nameError; ?></span>
                      <input type="text" name="name" id="name" class="form-control" value="<?php echo escape($result[0]["name"]); ?>">
                  </div>

                  <div class="form-group">
                      <label for="description">description</label><span class="text-danger ml-3"><?php echo empty($desError) ? '': "*".$desError; ?></span>
                      <input type="description" name="description" id="description" class="form-control" value="<?php echo escape($result[0]['description']); ?>">
                  </div>

                  <div class="form-group">
                      <input type="submit" class="btn btn-success" name="" value="Submit">
                      <a href="category.php" class="btn btn-warning">Back</a>
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