<?php
if(!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}
$numOfrecs = 5;
$offset = ($pageno - 1) * $numOfrecs;
if(empty($_POST['search']) && empty($_COOKIE["search"])) {
    $stmt = $pdo -> prepare("SELECT * FROM posts ORDER BY id DESC");
    $stmt -> execute();
    $rawResult = $stmt -> fetchAll();
    $total_pages = ceil(count($rawResult) / $numOfrecs);

    $stmt = $pdo -> prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset,$numOfrecs");
    $stmt -> execute();
    $result = $stmt -> fetchAll();

}else{
    
    $searchKey = isset($_POST['search']) ? $_POST["search"] : $_COOKIE['search'];
    $stmt = $pdo -> prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
    // print_r($stmt);exit();
    $stmt -> execute();
    $rawResult = $stmt -> fetchAll();

    $total_pages = ceil(count($rawResult) / $numOfrecs);

    $stmt = $pdo -> prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfrecs");
    $stmt -> execute();
    $result = $stmt -> fetchAll();
}
?>









<?php

if ($result) {

  $i = 1;
  foreach($result as $value ) { ?>

<tr>
  <td><?php echo $i; ?></td>
  <td><?php echo escape($value["title"]); ?></td>
  <td>
  <?php echo escape(substr($value["content"],0,50)); ?>
  </td>
  <td>
    <div class="btn-group">
      <a href="edit.php?id=<?php echo $value['id'] ?>" class="btn btn-warning">Edit</a>
      <a href="delete.php?id=<?php echo $value['id'] ?>"
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










<li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
<li class="page-item <?php if($pageno <= 1){echo "disabled";} ?>">
<a class="page-link" href="<?php if($pageno <= 1 ){echo "#";}else{echo "?pageno=".($pageno-1);} ?>">Previous</a>
</li>
<li class="page-item"><a class="page-link" href="#"><?php echo $pageno; ?></a></li>
<li class="page-item <?php if($pageno >= $total_pages){echo "disabled";} ?>">
<a class="page-link" href="<?php if($pageno >= $total_pages){echo "#";}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
</li>
<li class="page-item"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>









else {
            $name = $_POST["name"];
            $desc = $_POST["description"];
            $category = $_POST["category"];
            $qty = $_POST["quantity"];
            $price = $_POST["price"];
    
            $stmt = $pdo -> prepare("UPDATE products SET name = :name , description = :description , category_id = :category, price = :price, quantity = :quantity");
    
            $result = $stmt->execute(
                array(":name" => $name, ":description" => $desc, ":category" => $category, ":price" => $price, ":quantity" => $qty)
            );
    
            if($result) {
                echo "<script>alert('Product Added');window.location.href='index.php';</script>";
            }
        }