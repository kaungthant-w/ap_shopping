<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AP Shopping</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">

  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <?php
        $link = $_SERVER["PHP_SELF"];
        $link_array = explode('/', $link);
        $page = end($link_array);
      ?>

      <?php
        if($page == "index.php" || $page == "category.php" || $page == "user_list.php") { ?>

          <?php if($page != 'order_list.php' && $page != 'weekly_report.php' && $page != 'monthly_report.php' && $page != 'royal_user.php' && $page != 'best_seller.php') { ?>

            <form method="post" class="form-inline ml-3"
            <?php if($page == "index.php") :?>
              action = "index.php"
              <?php elseif($page == "category.php") :?>
              action = "category.php"
              <?php elseif($page == "user_list.php") :?>
                action = "user_list.php"
                <?php endif; ?>
              >
              
            <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
              <div class="input-group input-group-sm" >
                <input type="search" name="search" class="form-control form-control-navbar" placeholder="search">
                <div class="input-group-append">
                  <button class="btn btn-sidebar btn-default" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
            </form>

          <?php }?>
        <?php } ?>


    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="#" class="brand-link">
        <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AP Shopping</span>
      </a>

      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block"><?php echo $_SESSION["username"]; ?></a>
          </div>
        </div>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="index.php" class="nav-link">
                <i class="nav-icon fas fa-th"></i>
                <p>
                  Product
                </p>
              </a>
            </li>

            <li class="nav-item">
            <a href="category.php" class="nav-link">
                <i class="nav-icon fas fa-list"></i>
                <p>
                  Category
                </p>
              </a>
            </li>

            <li class="nav-item">
            <a href="user_list.php" class="nav-link">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  User
                </p>
              </a>
            </li>

            <li class="nav-item">
            <a href="order_list.php" class="nav-link">
                <i class="nav-icon fas fa-table"></i>
                <p>
                  Order
                </p>
              </a>
            </li>
            <li class="nav-item menu-close">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Report
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="weekly_report.php" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Weekly Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="monthly_report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Monthly Report</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="royal_cus.php" class="nav-link ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Royal Cutomers</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="best_seller.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Best Seller Items</p>
                </a>
              </li>
            </ul>
          </li>
          </ul>
        </nav>
      </div>
    </aside>

    <div class="content-wrapper">
      <div class="content-header">
      </div>
