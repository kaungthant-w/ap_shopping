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

  if(empty($_POST["name"]) || empty($_POST["description"]) || empty($_POST["category"]) || empty($_POST["quantity"]) || empty($_POST["price"])|| empty($_FILES["image"])) {
    if(empty($_POST['name'])) {
        $nameError = "Product Name is required";
    }

    if(empty($_POST["description"])) {
        $desError = "Description is required";
    }

    if(empty($_POST["category"])) {
        $catError = "category is required";
    }

    if(empty($_POST["quantity"])) {
        $qtyError = "quantity is required";
    } elseif (is_numeric($_POST["quantity"]) != 1){
        $qtyError = "quantity should be integer value.";
    }


    if(empty($_POST["price"])) {
        $priceError = "price is required";
    } elseif (is_numeric($_POST["price"]) != 1){
        $priceError = "Price should be integer value.";
    }

    if(empty($_FILES["image"])) {
        $imageError = "image is required";
    }
  } else {//validation success
    if($_FILES['image']['name'] != null) {
        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file, PATHINFO_EXTENSION);

        if($imageType != "jpg" && $imageType != "jpeg" && $imageType != "png") {
            echo "<script>alert('Image should be jpg, jpeg and png.');</script>";
          } else {
            $id  = $_POST["id"];
            $name = $_POST["name"];
            $desc = $_POST["description"];
            $category = $_POST["category"];
            $qty = $_POST["quantity"];
            $price = $_POST["price"];
            $image = $_FILES["image"]["name"];

            move_uploaded_file($_FILES["image"]["tmp_name"], $file);

            $stmt = $pdo -> prepare("UPDATE products SET name = :name , description = :description , category_id = :category, price = :price, quantity = :quantity , image = :image WHERE id=:id");

            $result = $stmt->execute(
                array(":name" => $name, ":description" => $desc, ":category" => $category, ":price" => $price, ":quantity" => $qty, ":image" => $image, ":id" => $id)
            );

            if($result) {
                echo "<script>alert('Product is Updated.');window.location.href='index.php';</script>";
            }
        }
    }else{
        $id  = $_POST["id"];
        $name = $_POST["name"];
        $desc = $_POST["description"];
        $category = $_POST["category"];
        $qty = $_POST["quantity"];
        $price = $_POST["price"];

        $stmt = $pdo -> prepare("UPDATE products SET name = :name , description = :description , category_id = :category, price = :price, quantity = :quantity WHERE id=:id");

        $result = $stmt->execute(
            array(":name" => $name, ":description" => $desc, ":category" => $category, ":price" => $price, ":quantity" => $qty, ":id" => $id)
        );

        if($result) {
            echo "<script>alert('Product is Updated.');window.location.href='index.php';</script>";
        }
    }
  }
}

$stmt = $pdo -> prepare("SELECT * FROM products WHERE id=".$_GET['id']);
$stmt -> execute();
$result = $stmt -> fetchAll();
?>

<?php include "header.php"; ?>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form action="" method="post" enctype="multipart/form-data">
              <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
              <input name="id" type="hidden" value="<?php echo $result[0]['id']; ?>">
                <div class="form-group">
                    <label for="name">name</label><span class="text-danger ml-3"><?php echo empty($nameError) ? '': "*".$nameError; ?></span>
                    <input type="text" name="name" id="name" class="form-control" value="<?php echo escape($result[0]["name"]); ?>">
                </div>

                <div class="form-group">
                    <label for="description">description</label><span class="text-danger ml-3"><?php echo empty($desError) ? '': "*".$desError; ?></span>
                    <textarea name="description" class="form-control" id="description" cols="10" rows="10"><?php echo escape($result[0]["description"]); ?></textarea>
                </div>

                <div class="form-group">

                    <?php
                        $catStmt = $pdo -> prepare("SELECT * FROM categories");
                        $catStmt -> execute();
                        $catResult = $catStmt -> fetchAll();
                    ?>
                
                    <label for="category">category</label><span class="text-danger ml-3"><?php echo empty($catError) ? '': "*".$catError; ?></span>
                    <select name="category" class="form-control">
                        <option value="">Select Category</option>
                        <?php foreach($catResult as $value ) { ?>
                            <?php if($value["id"] == $result[0]['category_id']) : ?>
                                <option value="<?php echo $value['id']; ?>" selected><?php  echo $value["name"] ?></option>
                                <? else : ?>
                                <option value="<?php echo $value['id']; ?>"><?php  echo $value["name"] ?></option>
                            <?php endif ?>
                        <?php } ?>

                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label><span class="text-danger ml-3"><?php echo empty($qtyError) ? '': "*".$qtyError; ?></span>
                    <input type="number" name="quantity" id="quantity" class="form-control" value="<?php echo escape($result[0]["quantity"]); ?>" >
                </div>

                <div class="form-group">
                    <label for="price">Price</label><span class="text-danger ml-3"><?php echo empty($priceError) ? '': "*".$priceError; ?></span>
                    <input type="number" name="price" id="price" class="form-control" value="<?php echo escape($result[0]["price"]); ?>">
                </div>

                <div class="form-group">
                    <label for="image">image</label><span class="text-danger ml-3"><?php echo empty($imageError) ? '': "*".$imageError; ?></span>
                    <img src="./images/<?php echo escape($result[0]['image']); ?>" alt="" class="form-control w-25 h-25 mb-3">
                    <input type="file" name="image" id="image" class="form-control">
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