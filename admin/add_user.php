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

include "header.php";

if($_POST) {
  if(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"]) || empty($_POST["phone"]) || strlen($_POST['password']) < 4) {
    if(empty($_POST["name"])) {
      $nameError = 'name cannot be empty';
    }
    if(empty($_POST["email"])) {
      $emailError = "email cannot be empty";
    }

    if(empty($_POST["phone"])) {
      $phoneError = "phone cannot be empty";
    }

    if(empty($_POST["password"])) {
      $passwordError = "password cannot be empty";
    }
    
    if(strlen($_POST['password']) < 4){
      $passwordError = "Password should be 4 characters at least";
    } 
  }else{
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    if(empty($_POST["role"])) {
      $role = 0;
    } else {
      $role = 1;
    }
  
    $stmt = $pdo -> prepare("SELECT * FROM users WHERE email = :email");

    $stmt -> bindValue(":email", $email);
    $stmt -> execute();
    $user = $stmt -> fetch(PDO::FETCH_ASSOC);

    if($user) {
      echo "<script>alert('Email duplicated!')</script>";
    } else {
      $stmt = $pdo -> prepare("INSERT INTO users(name, email, phone, password, role)VALUES(:name, :email, :phone, :password, :role)");

      $result = $stmt -> execute(
          array(':name' => $name, ':email' => $email, ':phone' => $phone , ':password' => $password, ':role' => $role)
      );

      if($result) {
          echo "<script>alert('Successfully user added.');window.location.href='user_list.php';</script>";
      }
    }
  }
}
?>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Add User</h3>
          </div>
          <div class="card-body w-50 mx-auto">
            <form action="" method="post" enctype="multipart/form-data">
              <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
              <div class="form-group">
                <label for="name">Username</label><span class="text-danger ml-3"><?php echo empty($nameError) ? '': "*".$nameError; ?></span>
                <input type="text" name="name" class="form-control">
              </div>

              <div class="form-group">
                <label for="email">email</label><span class="text-danger ml-3"><?php echo empty($emailError) ? '': "*".$emailError; ?></span>
                <input type="email" name="email" class="form-control">
              </div>

              <div class="form-group">
                <label for="name">Phone</label><span class="text-danger ml-3"><?php echo empty($phoneError) ? '': "*".$phoneError; ?></span>
                <input type="text" name="phone" class="form-control">
              </div>

              <div class="form-group">
                <label for="password">password</label><span class="text-danger ml-3"><?php echo empty($passwordError) ? '': "*".$passwordError; ?></span>
                <input type="password" name="password" class="form-control">
              </div>

              <div class="form-group form-check">
                <input type="checkbox" name="role" value="1" class="form-check-input" checked>
                <label for="role">Permission for Admin</label>
              </div>

              <button type="submit" class="btn btn-primary">Add User</button>
              <button class="btn"><a href="user_list.php" class="btn btn-warning">Back</a></button>
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
