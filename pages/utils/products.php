<?php
include 'process.php';
session_start();
if (!isset($_SESSION['group'])) {
  header('location: ../logout.php');
}else{
  $username = $_SESSION['username'];
  $group = $_SESSION['group'];
}
?>
<!DOCTYPE  html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PREMIER| Product Categories</title>
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
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
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
      <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">PREMIER POS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $username; ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="../../index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>

          </li>
          <?php
          // $conn = mysqli_connect("localhost",'root','','premierdb')or die($conn->error);
          $rights = $conn->query("SELECT * FROM security_groups WHERE id='$group' ")or die($conn->error);
          if (mysqli_num_rows($rights) == 1) {
            $row = $rights->fetch_array();

          ?>
          <?php if (!($row["manage_categories"] == 0 && $row["manage_products"] == 0 && $row["manage_suppliers"] == 0)){?>
          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Inventory
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if ($row["manage_categories"] == 1):?>
              <li class="nav-item">
                <a href="../inventory/categories.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Categories</p>
                </a>
              </li>
            <?php endif;  ?>
            <?php if ($row["manage_products"] == 1):?>
              <li class="nav-item">
                <a href="../inventory/products.php" class="nav-link active">
                  <i class="far fa-dot-circle nav-icon"></i>
                  <p>Products</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../inventory/product_list.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Product List</p>
                </a>
              </li>
            <?php endif;  ?>
            <?php if ($row["manage_suppliers"] == 1):?>
              <li class="nav-item">
                <a href="../inventory/suppliers.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Suppliers</p>
                </a>
              </li>
            <?php endif;  ?>
            </ul>
          </li>
          <?php } ?>
          <?php if (!($row["receive_stock"] == 0 && $row["transfer_stock"] == 0 && $row["issue_stock"] == 0 && $row["request_stock"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                Stock
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if ($row["receive_stock"] == 1):?>
              <li class="nav-item">
                <a href="../stock/receive.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Recieve Stock</p>
                </a>
              </li>
            <?php endif;  ?>
            <?php if ($row["transfer_stock"] == 1):?>
              <li class="nav-item">
                <a href="../stock/transfer.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Transfer Stock</p>
                </a>
              </li>
            <?php endif;  ?>
            <?php if ($row["issue_stock"] == 1):?>
              <li class="nav-item">
                <a href="../stock/issue.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Issue Stock</p>
                </a>
              </li>
            <?php endif;  ?>
            <?php if ($row["request_stock"] == 1):?>
              <li class="nav-item">
                <a href="../stock/request.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Request Stock</p>
                </a>
              </li>
            <?php endif;  ?>
            </ul>
          </li>
          <?php } ?>
          <?php if (!($row["make_sales"] == 0 && $row["clear_sales"] == 0 && $row["credit_sales"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Sales
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if ($row["make_sales"] == 1):?>
              <li class="nav-item">
                <a href="../sales/addsales.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Sales</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["clear_sales"] == 1):?>
              <li class="nav-item">
                <a href="../sales/clearsales.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Clear Sales</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["credit_sales"] == 1):?>
              <li class="nav-item">
                <a href="../sales/creditsale.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Credit Sale</p>
                </a>
              </li>
              <?php endif;  ?>
            </ul>
          </li>
          <?php } ?>
          <?php if (!($row["manage_account_categories"] == 0 && $row["manage_accounts"] == 0 && $row["manage_expenses"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-check-alt"></i>
              <p>
                Accounts
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if ($row["manage_account_categories"] == 1):?>
              <li class="nav-item">
                <a href="../accounts/account_categories.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Accounts Categories</p>
                </a>
              </li>
              <?php endif;  ?>
              <!-- Specific Accounts -->
              <li class="nav-item">
                <a href="../accounts/expenses.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Expenses</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../accounts/shorts.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Shorts/ Excesses</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../accounts/supplier.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Supplier Accounts</p>
                </a>
              </li>
              <!-- End of Specific Accounts -->
              <?php if ($row["manage_accounts"] == 1):?>
              <li class="nav-item">
                <a href="../accounts/account_new.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Account</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["manage_accounts"] == 1):?>
              <li class="nav-item">
                <a href="../accounts/account_manage.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Accounts</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["manage_expenses"] == 1):?>
              <li class="nav-item">
                <a href="../expense/expense_book.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Book Expenses</p>
                </a>
              </li>
            <?php endif;  ?>
            </ul>
          </li>
          <?php } ?>
          <?php if (!($row["manage_account_categories"] == 0 && $row["manage_accounts"] == 0 && $row["manage_expenses"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Payment Accounts
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../payment-accounts/account-create.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Accounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../payment-accounts/accounts-list.php" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List Accounts</p>
                </a>
              </li>
            </ul>
          </li>
        <?php } ?>
          <?php if ($row["manage_expenses"] == 1):?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link ">
              <i class="nav-icon fas fa-minus-circle"></i>
              <p>
                Expenses
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../accounts/expense-add.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Expenses</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../accounts/expense_categories.php" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Expenses Categories</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../accounts/expenses_list.php" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List Expenses</p>
                </a>
              </li>
            </ul>
          </li>

          <?php endif;  ?>
          <!-- <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-money-bill-alt"></i>
              <p>
                Expenses
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">

            </ul>
          </li> -->
          <?php if (!($row["manage_customer_groups"] == 0 && $row["manage_customers"] == 0 && $row["manage_price_rules"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-portrait"></i>
              <p>
                Customers
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if ($row["manage_customer_groups"] == 1):?>
              <li class="nav-item">
                <a href="../customers/customer_groups.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer Groups</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../customers/customer-group-add.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Customer Groups</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["manage_customers"] == 1):?>
              <li class="nav-item">
                <a href="../customers/customers.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customers</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["manage_price_rules"] == 1):?>
              <li class="nav-item">
                <a href="../customers/pricerules.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Price Rules</p>
                </a>
              </li>
              <?php endif;  ?>
            </ul>
          </li>
          <?php } ?>
          <?php if (!($row["sales_reports"] == 0 && $row["transfer_reports"] == 0 && $row["recieve_reports"] == 0 && $row["recieve_reports"] == 0 && $row["issue_reports"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-bookmark"></i>
              <p>
                Sales Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if ($row["sales_reports"] == 1):?>
              <li class="nav-item">
                <a href="../stock_reports/sales.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sales Reports</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["transfer_reports"] == 1):?>
              <li class="nav-item">
                <a href="../stock_reports/transfers.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Transfers</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["recieve_reports"] == 1):?>
              <li class="nav-item">
                <a href="../stock_reports/receives.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Receive</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["issue_reports"] == 1):?>
              <li class="nav-item">
                <a href="../stock_reports/issues.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Stock Issues</p>
                </a>
              </li>
              <?php endif;  ?>
            </ul>
          </li>
        <?php } ?>
          <?php if ($row["reports"] == 1):?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../reports/cash_reconciliation.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Cash Reconciliation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../reports/cashless_payments.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mpesa/Cheque Payments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../reports/credit_sales.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Credit Sales</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../reports/discount_analysis.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Discounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../reports/expenses.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Expenses Account</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../reports/performance.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Business Performance</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../reports/short_excess.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Short/Excess Analysis</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif;  ?>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="fas fa-cog"></i>
            <p>Settings
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../settings/discounts.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Discounts</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../settings/tax_rates.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Tax Rates</p>
              </a>
            </li>
          </ul>
        </li>
        <?php if (!($row["manage_user_groups"] == 0 && $row["manage_users"] == 0 && $row["manage_user_rights"] == 0)){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="fas fa-lock"></i>
              <p>
                Security
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <?php if ($row["manage_users"] == 1):?>
              <li class="nav-item">
                <a href="../security/registered_users.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["manage_users"] == 1):?>
              <li class="nav-item">
                <a href="../security/users.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Add Users</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["manage_user_groups"] == 1):?>
              <li class="nav-item">
                <a href="../security/user_groups.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User Groups</p>
                </a>
              </li>
              <?php endif;  ?>
              <?php if ($row["manage_user_rights"] == 1):?>
              <li class="nav-item">
                <a href="../security/user_rights.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Rights</p>
                </a>
              </li>
              <?php endif;  ?>
            </ul>
          </li>
          <?php }} ?>
          <li class="nav-item">
            <a href="../logout.php" class="nav-link">
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
            <h1 class="m-0 text-dark">Inventory</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inventory</a></li>
              <li class="breadcrumb-item active">Products</li>
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
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Products</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="card-body">
                  <div class="row">
                  <input type="hidden" name="id" value="<?php echo $id; ?>">
                  <div class="col-md-4 form-group">
                    <label for="exampleInputEmail1">Product Name <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter name" name="product_name" value="<?php echo $product_name; ?>">
                    <span style="color: red;"><?php echo $product_err; ?></span>
                  </div>
                  <div class="col-md-4 form-group">
                    <label>Category <span style="color: red;">*</span></label>
                    <select name="category" class="form-control" name="category">
                      <?php
                        $category = $conn->query("SELECT * FROM product_categories") or die($conn->error);
                      ?>
                        <?php while ($row = $category->fetch_assoc()):?>
                          <option class="dropdown-item" value="<?php echo $row['name']; ?>"><?php echo $row['name'];?></option>
                        <?php endwhile;?>
                    </select>
                  </div>

                  <div class="col-md-4 form-group">
                    <label for="shellcost">Cost of Liquid <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Cost of liquid i.e. exclude empties price" name="cost" value="<?php echo $cost; ?>">
                    <span style="color: red;"><?php echo $liquid_cost_err; ?></span>
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">RRP Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="selling_cost" value="<?php echo $selling_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Stockist Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="stockist_cost" value="<?php echo $stockist_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Wholesale Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="wholesale_cost" value="<?php echo $wholesale_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Distributor Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="distributor_cost" value="<?php echo $distributor_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Retail Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="retail_cost" value="<?php echo $retail_cost; ?>">
                  </div>
                </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <?php if ($product_update) :?>
                  <button type="submit" class="btn btn-primary" name="product_update">Update</button>
                  <?php else : ?>
                  <button type="submit" class="btn btn-primary" name="product_submit">Submit</button>
                <?php endif; ?>
                </div>
              </div>
            </form>
          </div>
        </div>
            <!-- /.card -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Registered Products</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Cost</th>
                      <th>RRP Price</th>
                      <th>Wholesale Price</th>
                      <th>Stockist Price</th>
                      <th>Retail Price</th>
                      <th>Distributor Price</th>
                      <th colspan="2">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $number =1;
                    // $conn = mysqli_connect('localhost','root','','premierdb') or die($conn->error);
                    $res = $conn->query("SELECT * FROM products") or die($conn->error);
                    while ($row= $res->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?php echo $number; ?></td>
                      <td><?php echo $row['name']; ?></td>
                      <td><?php echo $row['category']; ?></td>
                      <td><?php echo $row['price']; ?></td>
                      <td><?php echo $row['selling_cost']; ?></td>
                      <td><?php echo $row['wholesale']; ?></td>
                      <td><?php echo $row['stockist']; ?></td>
                      <td><?php echo $row['retail']; ?></td>
                      <td><?php echo $row['distributor']; ?></td>
                      <td style="width: 40px"><a href="products.php?product_edit=<?php echo $row['id'];?>"><button class="btn btn-success">Edit</button></a></td>
                      <td style="width: 40px"><a href="products.php?product_delete=<?php echo $row['id'];?>"><button class="btn btn-danger">Delete</button></a></td>

                    </tr>
                    <?php
                      $number++;
                    endwhile;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
              <!-- /.card-body -->
            <!-- /.card -->

        <!-- /.row -->

    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Premiersoft Systems</a>.</strong> All rights reserved.
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
<!-- fullCalendar 2.2.5 -->
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/fullcalendar/main.min.js"></script>
<script src="../../plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="../../plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="../../plugins/fullcalendar-interaction/main.min.js"></script>
<script src="../../plugins/fullcalendar-bootstrap/main.min.js"></script>
<!-- Page specific script -->
<script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendarInteraction.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------

    new Draggable(containerEl, {
      itemSelector: '.external-event',
      eventData: function(eventEl) {
        console.log(eventEl);
        return {
          title: eventEl.innerText,
          backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
        };
      }
    });

    var calendar = new Calendar(calendarEl, {
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      //Random default events
      events    : [
        {
          title          : 'All Day Event',
          start          : new Date(y, m, 1),
          backgroundColor: '#f56954', //red
          borderColor    : '#f56954' //red
        },
        {
          title          : 'Long Event',
          start          : new Date(y, m, d - 5),
          end            : new Date(y, m, d - 2),
          backgroundColor: '#f39c12', //yellow
          borderColor    : '#f39c12' //yellow
        },
        {
          title          : 'Meeting',
          start          : new Date(y, m, d, 10, 30),
          allDay         : false,
          backgroundColor: '#0073b7', //Blue
          borderColor    : '#0073b7' //Blue
        },
        {
          title          : 'Lunch',
          start          : new Date(y, m, d, 12, 0),
          end            : new Date(y, m, d, 14, 0),
          allDay         : false,
          backgroundColor: '#00c0ef', //Info (aqua)
          borderColor    : '#00c0ef' //Info (aqua)
        },
        {
          title          : 'Birthday Party',
          start          : new Date(y, m, d + 1, 19, 0),
          end            : new Date(y, m, d + 1, 22, 30),
          allDay         : false,
          backgroundColor: '#00a65a', //Success (green)
          borderColor    : '#00a65a' //Success (green)
        },
        {
          title          : 'Click for Google',
          start          : new Date(y, m, 28),
          end            : new Date(y, m, 29),
          url            : 'http://google.com/',
          backgroundColor: '#3c8dbc', //Primary (light-blue)
          borderColor    : '#3c8dbc' //Primary (light-blue)
        }
      ],
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }
    });

    calendar.render();
    // $('#calendar').fullCalendar()

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      ini_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
  })
</script>
</body>
</html>
