<?php include '../utils/conn.php'; ?>
<?php
session_start();
if (!isset($_SESSION['group'])) {
  header('location: ../utils/logout.php');
}else{
  $username = $_SESSION['username'];
  $group = $_SESSION['group'];
}

$amount = $amt_err = "";
$valid = true;
if (isset($_POST['short_submit'])) {
  $name = $_POST['name'];
  $amount = $_POST['amount'];
  if (empty($amount)) {
    $amt_err = "Amount is required";
    $valid = false;
  }
  if ($valid) {
    $check = $conn->query("SELECT * FROM shorts WHERE user='$name'") or die($conn->error);
    if (mysqli_num_rows($check) > 0) {
      $amts = $conn->query("SELECT sht_amt FROM shorts WHERE user='$name'") or die($conn->error);
      $row = $amts->fetch_array();
      $amount += $row['sht_amt'];
      $conn->query("UPDATE shorts SET sht_amt='$amount' WHERE user='$name'") or die($conn->error);
      $conn->query("UPDATE stock_mobile SET clear_status='1' WHERE store_owner='$name'") or die("Connection error");
      header('location: clear.php');
    }else{
      $reason = "Sales short";
      $conn->query("INSERT INTO `shorts`(`user`, `reason`, `sht_amt`) VALUES ('$name','$reason','$amount')") or die($conn->error);
      $conn->query("UPDATE stock_mobile SET clear_status='1' WHERE store_owner='$name'") or die("Connection error");
      header('location: clear.php');
    }
  }
}
 ?>
<!DOCTYPE  html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>GEMAD</title>
  <link rel="icon" type="image/x-icon" href="../../dist/img/favicon.ico">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="../../index.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
        </a>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../pos/">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index.php" class="brand-link">
     <img src="../../dist/img/favicon.ico" alt="Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Premiersoft Warehouse</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">GEMAD LIMITED</a>
        </div>
      </div>
      <?php
         // $conn = mysqli_connect("localhost",'root','','premierdb')or die($conn->error);
         $rights = $conn->query("SELECT * FROM security_groups WHERE id='$group' ")or die($conn->error);
         if (mysqli_num_rows($rights) == 1) {
           $row = $rights->fetch_array();
           $group = $row["group_name"];
      ?>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php if(!($row["view_dashboard"] == 0)) { ?>
            <li class="nav-item has-treeview">
             <a href="../../index.php" class="nav-link">
               <i class="nav-icon fas fa-tachometer-alt"></i>
               <p>
                 Dashboard
               </p>
             </a>
           </li>
         <?php } if (!($row["manage_users"] == 0 && $row["manage_user_groups"] == 0)) {?>
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                User Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if (!($row["manage_users"] == 0)) { ?>
              <li class="nav-item">
                <a href="../users/users.php" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
            <?php } if (!($row["manage_user_groups"] == 0)) { ?>
              <li class="nav-item">
                <a href="../users/groups.php" class="nav-link">
                  <i class="fas fa-briefcase nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>
        <?php } if (!($row["manage_suppliers"] == 0 && $row["manage_customers"] == 0 && $row["manage_customer_groups"] == 0)) {?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-address-book"></i>
              <p>
                Contacts
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if (!($row["manage_suppliers"] == 0)){ ?>
              <li class="nav-item">
                <a href="../contacts/suppliers.php" class="nav-link">
                  <i class="fas fa-star nav-icon"></i>
                  <p>Suppliers</p>
                </a>
              </li>
            <?php } if (!($row["manage_customers"] == 0)){ ?>
              <li class="nav-item">
                <a href="../contacts/customers.php" class="nav-link">
                  <i class="fas fa-star nav-icon"></i>
                  <p>Customers</p>
                </a>
              </li>
            <?php } if (!($row["manage_customer_groups"] == 0)){ ?>
              <li class="nav-item">
                <a href="../contacts/groups.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer Groups</p>
                </a>
              </li>
              <?php }?>
            </ul>
          </li>
        <?php }if (!($row["manage_products"] == 0 && $row["manage_categories"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cubes"></i>
              <p>
                Products
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if (!($row["manage_products"] == 0)){?>
              <li class="nav-item">
                <a href="../products/list.php" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>Products List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../products/products_create.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../products/price_list.php" class="nav-link">
                  <i class="fas fa-barcode nav-icon"></i>
                  <p>Price Lists</p>
                </a>
              </li>
            <?php } if (!($row["manage_categories"] == 0)){ ?>
              <li class="nav-item">
                <a href="../products/categories.php" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Product Categories</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>
        <?php }if (!($row["receive_stock"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-arrow-circle-up"></i>
              <p>
                Purchases
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../purchases/list.php" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List Purchases</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../purchases/purchases_create.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Purchase</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../purchases/returns.php" class="nav-link">
                  <i class="fas fa-undo nav-icon"></i>
                  <p>List Purchases Returns</p>
                </a>
              </li>
            </ul>
          </li>
        <?php }if (!($row["make_sales"] == 0 && $row["clear_sales"] == 0 && $row["issue_stock"] == 0)){?>
          <li class="nav-item has-treeview menu-open ">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-arrow-circle-down"></i>
              <p>
                Sales
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../sales/list.php" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>All Sales</p>
                </a>
              </li>
              <?php if (!($row["sale_from_main"] == 0)) { ?>
              <li class="nav-item">
                <a href="../sales/sales_create.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Sales</p>
                </a>
              </li>
            <?php } ?>
              <li class="nav-item">
                <a href="../sales/view_stock.php" class="nav-link">
                  <i class="fas fa-truck nav-icon"></i>
                  <p>View Stock</p>
                </a>
              </li>
              <?php if (!($row["issue_stock"] == 0)){ ?>
              <li class="nav-item">
                <a href="../sales/request.php" class="nav-link">
                  <i class="fas fa-handshake nav-icon"></i>
                  <p>Assign Stock</p>
                </a>
              </li>
            <?php } if (!($row["clear_sales"] == 0)){ ?>
              <li class="nav-item">
                <a href="../sales/clear.php" class="nav-link active">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Clear Sales</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>

          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Stock Adjustment
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../adjustments/list.php" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List Stock Adjustment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../adjustments/adjustment_create.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Stock Adjustment</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-minus-circle"></i>
              <p>
                Expenses
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
          <?php if (!($row["manage_expenses"] == 0)){?>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../accounts/list.php" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List Expenses</p>
                </a>
              </li>
            <?php } ?>
              <li class="nav-item">
                <a href="../accounts/account_create.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Expense</p>
                </a>
              </li>
              <?php if (!($row["manage_expenses"] == 0)){?>
              <li class="nav-item">
                <a href="../accounts/account_groups.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Expense Categories</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-check-alt"></i>
              <p>
                Payment Accounts
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../accounts/list.php" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List Payment Accounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../accounts/accounts_create.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Payment Account</p>
                </a>
              </li>
            </ul>
          </li>
          <?php if (!($row["settings"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Settings
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../settings/discounts.php" class="nav-link">
                  <i class="fas fa-cogs nav-icon"></i>
                  <p>Discounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../settings/tax_rates.php" class="nav-link">
                  <i class="fas fa-bolt nav-icon"></i>
                  <p>Taxes</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../settings/pay_terms.php" class="nav-link">
                  <i class="fas fa-file nav-icon"></i>
                  <p>Pay Terms</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../settings/stores.php" class="nav-link">
                  <i class="fas fa-map-marker nav-icon"></i>
                  <p>Stores</p>
                </a>
              </li>
            </ul>
          </li>
        <?php } }?>
        <li class="nav-item">
          <a href="../utils/logout.php" class="nav-link">
            <i class="nav-icon fas fa-undo-alt"></i>
            <p>Logout</p>
          </a>
        </li>
            </ul>
          </nav>
          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Shorts</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Shorts</a></li>
              <li class="breadcrumb-item active">New</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add A Short</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="card-body">
                  <span style="color: red;">Sections marked with * must be filled</span>
                  <input type="hidden" name="id" value="<?php echo $id; ?>">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name: <span style="color: red;">*</span></label>
                    <select class="form-control" name="name" readonly>
                      <?php
                      $users = $conn->query("SELECT id, name FROM users") or die($conn->error);
                      $userID = $_GET['clear'];
                      while ($row = $users->fetch_assoc()) {
                        if ($row['id'] == $userID) {
                          echo "<option value=".$row['id']." selected>".$row['name']."</option>";
                        }else{
                          echo "<option value=".$row['id'].">".$row['name']."</option>";
                        }
                      }
                       ?>
                    </select>
                  </div>
                  <div class="form-group" id="costofshells">
                    <label for="discount_rate">Short Amount:<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="amount" placeholder="Enter amount in KES e.g. 2000" value="">
                    <span style="color: red;"><?php echo $amt_err; ?></span>
                  </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary" name="short_submit">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
            <!-- /.card -->
            <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Shorts</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>User</th>
                      <th>Total Short</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $number =1;
                    $res = $conn->query("SELECT * FROM `shorts`") or die($conn->error);
                    while ($row= $res->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?php echo $number; ?></td>
                      <td><?php echo $row['user']; ?></td>
                      <td><?php echo $row['sht_amt'];?></td>
                    </tr>
                    <?php
                      $number++;
                    endwhile;
                    ?>
                  </tbody>
                </table>
              </div>

            </div>
            <!-- /.card -->
          </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Premiersoft Technologies</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI -->
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>

</body>
</html>
