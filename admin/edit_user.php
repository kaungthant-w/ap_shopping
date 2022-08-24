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
      if(empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["phone"])) {
        if(empty($_POST["name"])) {
          $nameError = 'name cannot be empty';
        }
        if(empty($_POST["email"])) {
          $emailError = "email cannot be empty";
        }

        if(empty($_POST["phone"])) {
          $phoneError = "phone cannot be empty";
        }
      } elseif (!empty($_POST["password"]) && strlen($_POST['password']) < 4) {
        $passwordError = "Password should be 4 characters at least";

      } else {
        $id = $_POST["id"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        if(empty($_POST["role"])) {
          $role = 0;
        } else {
          $role = 1;
        }

        $stmt = $pdo -> prepare("SELECT * FROM users WHERE email = :email AND id != :id");
        $stmt -> execute(array(":email"=> $email, ':id' => $id));
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);
        
        if($user){
            echo "<script>alert('Email duplicated')</script>";
        } else {
          $stmt = $pdo -> prepare("UPDATE users SET name='$name', email='$email', phone='$phone', role='$role' WHERE id='$id'");
          $result = $stmt -> execute();
          if($result) {
              echo "<script>alert('Successfully Updated.');window.location.href='user_list.php';</script>";
          }
        } 
      } 
    }

    $stmt = $pdo -> prepare("SELECT * FROM users WHERE id=".$_GET['id']);
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
                  <div class="form-group">
                      <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                      <label for="name">name</label><span class="text-danger ml-3"><?php echo empty($nameError) ? '': "*".$nameError; ?></span>
                      <input type="text" name="name" id="name" class="form-control" value="<?php echo escape($result[0]["name"]); ?>">
                  </div>

                  <div class="form-group">
                      <label for="email">email</label><span class="text-danger ml-3"><?php echo empty($emailError) ? '': "*".$emailError; ?></span>
                      <input type="email" name="email" id="email" class="form-control" value="<?php echo escape($result[0]['email']); ?>">
                  </div>

                  <div class="form-group">
                      <label for="phone">phone</label><span class="text-danger ml-3"><?php echo empty($phoneError) ? '': "*".$phoneError; ?></span>
                      <input type="number" name="phone" id="phone" class="form-control" value="<?php echo escape($result[0]['phone']); ?>">
                  </div>

                  <div class="form-group">
                    <label for="password">password</label><span class="text-danger ml-3"><?php echo empty($passwordError) ? '': "*".$passwordError; ?></span>
                    <div class="mt-1">The user already has a password</div>
                    <input type="password" name="password" class="form-control">
                  </div>

                  <div class="form-group form-check">
                      <input type="checkbox" name="role" class="form-check-input" id="role" value="1" <?php echo $result[0]["role"] ? "checked" : ""; ?>>
                      <label for="role">Permission for admin</label>
                  </div>

                  <div class="form-group">
                      <input type="submit" class="btn btn-success" name="" value="Submit">
                      <a href="user_list.php" class="btn btn-warning">Back</a>
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