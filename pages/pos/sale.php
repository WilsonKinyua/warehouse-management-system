<?php
include 'process.php';
$message = "";
if(isset($_POST['update_cart'])){
foreach($_POST['quantity'] as $key => $val) {
    if($val==0) {
        unset($_SESSION['cart'][$key]);
    }else{
        $_SESSION['cart'][$key]['quantity']=$val;
    }
}
header('location: sales_create.php');
}

if(isset($_POST['clear_cart'])){
  unset($_SESSION['cart']);
  header('location: sales_create.php');
}


if(isset($_GET['action']) && $_GET['action']=="add"){
        if ($_GET['name']) {
          $name = $_GET['name'];
          $product = $conn ->query("SELECT id FROM products WHERE name='$name'") or die($conn->error);
          $res = $product->fetch_array();
          $id = $res['id'];
        }else{
          $id=intval($_GET['id']);
        }

        if (isset($_GET['customer'])) {
          $cid = $_GET['customer'];
          $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE id='$cid'") or die($conn->error);
          $array = $cust->fetch_array();
          $pricelist = $array['pricelist'];
          if ($pricelist==1) {
            $price = 'wholesale';
          }elseif ($pricelist == 2) {
            $price = 'stockist';
          }elseif ($pricelist == 3) {
            $price = 'distributor';
          }elseif ($pricelist == 4) {
            $price = 'retail';
          }else {
            $price = 'retail';
          }
        }

        if(isset($_SESSION['cart'][$id])){
            $_SESSION['cart'][$id]['quantity']++;
        }else{
            $sql_s="SELECT * FROM products WHERE id={$id}";
            $query_s=$conn->query($sql_s);
            if(mysqli_num_rows($query_s)!=0){
                $row_s=$query_s->fetch_array();
                $_SESSION['cart'][$row_s['id']]=array(
                        "quantity" => 1,
                        "price" => $row_s['price']
                    );
            }else{
                $message="This product id it's invalid!";
            }

        }
        header('location: sales_create.php');
    }
    if(isset($_GET['action']) && $_GET['action']=="remove"){

            $id=intval($_GET['id']);
            unset($_SESSION['cart'][$id]);
        }
    if(isset($_GET['page'])){

        $pages=array("products", "cart");

        if(in_array($_GET['page'], $pages)) {

            $_page=$_GET['page'];

        }else{

            $_page="products";

        }

    }else{

        $_page="products";

    }
?>
<?php
if (!isset($_SESSION['group'])) {
  header('location: ../utils/logout.php');
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
  <title>Premiersoft Warehouse</title>
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
       <!--  <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>  -->
    </ul>

    <!-- SEARCH FORM 
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> -->

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
          <a href="#" class="d-block">Gemad Agencies Ltd</a>
        </div>
      </div>
      <?php
         // $conn = mysqli_connect("localhost",'root','','premierdb')or die($conn->error);
         $rights = $conn->query("SELECT * FROM security_groups WHERE id='$group' ")or die($conn->error);
         if (mysqli_num_rows($rights) == 1) {
           $row = $rights->fetch_array();
           $group = $row["group_name"];
      ?><!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <?php 
          
          if(!($row["view_dashboard"] == 0)) { ?>
            <li class="nav-item has-treeview">
             <a href="../../index.php" class="nav-link">
               <i class="nav-icon fas fa-tachometer-alt"></i>
               <p>
                 Dashboard
               </p>
             </a>
           </li>
         <?php }
         
         if (!($row["manage_users"] == 0 && $row["manage_user_groups"] == 0)) {?>
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                User Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php 
            
            if (!($row["manage_users"] == 0)) { ?>
              <li class="nav-item">
                <a href="../users/users.php" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
            <?php } 
            
            if (!($row["manage_user_groups"] == 0)) { ?>
              <li class="nav-item">
                <a href="../users/groups.php" class="nav-link">
                  <i class="fas fa-briefcase nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>
        <?php } 
        
        if (!($row["manage_suppliers"] == 0 && $row["manage_customers"] == 0 && $row["manage_customer_groups"] == 0)) {?>
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
        <?php }
        
        if (!($row["manage_products"] == 0 && $row["manage_categories"] == 0)) {?>
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
        <?php }
        
        if (!($row["receive_stock"] == 0)){?>
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
            </ul>
          </li>
        <?php }
        
        if (!($row["make_sales"] == 0 && $row["clear_sales"] == 0 && $row["issue_stock"] == 0)) {?>
          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-arrow-circle-down"></i>
              <p>
                Sales
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
               <?php if (!($row["sale_from_main"] == 0)) { ?>
              <li class="nav-item">
                <a href="../sales/sales_create.php" class="nav-link active">
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
                <a href="../sales/clear.php" class="nav-link">
                  <i class="fas fa-circle nav-icon"></i>
                  <p>Clear Sales</p>
                </a>
              </li>
            <?php } ?>
            </ul>
          </li>
          <?php if (!($row["adjust_stock"] == 0)): ?>

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
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Adjust Stock</p>
                </a>
              </li>

            </ul>
          </li>
          <?php endif; ?>
          
          <?php } ?>
          
          
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-minus-circle"></i>
              <p>
                Expenses
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
                
          <?php if (!($row["manage_expenses"] == 0)){?>
              <li class="nav-item">
                <a href="../accounts/list.php" class="nav-link">
                  <i class="fas fa-list nav-icon"></i>
                  <p>List Expenses</p>
                </a>
              </li>
            <?php }if (!($row["manage_expenses"] == 0)) {?>
              <li class="nav-item">
                <a href="../accounts/account_create.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add Expense</p>
                </a>
              </li>
              <?php } if (!($row["manage_expenses"] == 0)){?>
              <li class="nav-item">
                <a href="../accounts/account_groups.php" class="nav-link">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Expense Categories</p>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php if ($row["manage_accounts"] == 1) { ?>
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
         
        <?php } ?>
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
        
<!--*******************REPORTS Side Nav *****************************-->

        <?php if (!($row["reports"] == 0 )){?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fas fa-chart-bar"></i>
              <p>
                Reports
             <i class="fas fa-angle-left right"></i>     
              </p>
            </a>
            <?php if (!($row["reports"] == 0 )) { ?>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../reports/salessummary.php" class="nav-link">
                  <i class="fa fas fa-chart-line nav-icon"></i>
                  <p>Sales Summary</p>
                </a>
              </li>
              <?php } if (!($row["reports"] == 0 )) { ?>
              <li class="nav-item">
                <a href="../reports/salesdetail.php" class="nav-link">
                  <i class="fa fas fa-search-dollar nav-icon "></i>
                  <p>Sales Detail</p>
                </a>
              </li>               
              <?php } if (!($row["reports"] == 0 )) { ?>
              </li> 
                <?php } if (!($row["reports"] == 0 )) { ?>
              <li class="nav-item">
                <a href="../reports/itemsale.php" class="nav-link">
                  <i class="fa fas fa-tags nav-icon "></i>
                  <p>Item Sales</p>
                </a>
              </li> 
                <?php } if (!($row["reports"] == 0 )) { ?>
              <li class="nav-item">
                <a href="../reports/inventory.php" class="nav-link">
                  <i class="fa fas fa-hourglass-half nav-icon "></i>
                  <p>Inventory</p>
                </a>
              </li>
              <?php } if (!($row["reports"] == 0 )) { ?>
              <li class="nav-item">
                <a href="../reports/purchasedet.php" class="nav-link">
                  <i class="fa fas fa-truck nav-icon "></i>
                  <p>Purchases Detail</p>
                </a>
              </li>
               </ul>
            <?php }
            }?>
            
 <!--**************************************************************** -->        
        
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
    
  <script type="text/javascript">
    function pickCost() {
      var customer = document.getElementById('customer').value;
      window.location.href = "sales_create.php?customer="+customer;
    }
  </script>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Make Sale</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="sales_create.php" method="POST">
                <input type="hidden" name="salesman" value="<?php echo $username; ?>">
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Receipt Number: </label>
                        <input type="text" name="doc_number" value="INV-<?php echo round(microtime(true)*1000); ?>" class="form-control" readonly>
                      </div>
                      <div class="col-md-4">
                        <label for="customer_name">Customer: </label>
                        <select class="form-control" name="customer_name" id="customer" onchange="pickCost()">
                          <option value="-1"> Walkin Customer</option>
                          <?php

                           ?>
                          <?php if ($group != 'Salesman'){
                          $query = "SELECT id,cust_name FROM customers";
                          $res = $conn->query($query) or die($conn->error);
                          while($row = $res->fetch_assoc()){
                            if (isset($_GET['customer'])) {
                              $id = $_GET['customer'];
                              if ($row['id'] == $id) {
                                echo  "<option value=".$row['id']." selected> ".$row['cust_name']."</option>";
                              }else{
                                echo "<option value=".$row['id']."> ".$row['cust_name']."</option>";
                              }
                            }else{
                              echo "<option value=".$row['id']."> ".$row['cust_name']."</option>";
                            }
                          }}
                          ?>
                        </select>
                        <span style="color:red;"><?php echo $cust_err; ?></span>
                      </div>
                      <div class="col-md-4">
                        <label>Sale Date: </label>
                        <input type="date" name="sale_date" value="<?php echo date("Y-m-d"); ?>" class="form-control" readonly>
                      </div>
                      <div class="col-md-12">
                        <?php echo $message; ?>
                      </div>
                      <div class="col-md-2">
                        &nbsp;
                      </div>
                      <div class="col-md-8">
                        <label>Search for A Product:</label>
                        <input class="form-control" type="text" id="suggestion_textbox" />
                        </div>
                        <div class="col-md-2">
                          &nbsp;
                        </div>
                        <script src="js/jquery.min.js"></script>
                        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                        <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                        <script>

                      	$(document).ready(function(e){

                      		$("#suggestion_textbox").autocomplete({
                      			source:'search.php'
                      		});

                      	});
                        $('#suggestion_textbox').on('keypress', function(e) {
                          var code = e.keyCode || e.which;
                          if(code==13){
                              e.preventDefault();
                              let name = $("#suggestion_textbox").val();
                              window.location.href = "sales_create.php?page=products&action=add&name="+name;
                          }
                      });
                      	</script>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <form method="post" action="sales_create.php?page=cart">
                      <table class="table table-bordered table-striped col-md-11" style="width: 100%; margin-left: 2em;">
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Remove</th>
                        </tr>

                  <?php
                  if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                    echo "<td colspan='5'>No products in catalog</td>";
                  }else{
                    $price=0;
                    if (isset($_GET['customer'])) {
                      $cid = $_GET['customer'];
                      $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE customers.id='$cid'") or die($conn->error);
                      $array = $cust->fetch_array();
                      $pricelist = $array['pricelist'];
                      if ($pricelist==1) {
                        $price = 'wholesale';
                      }elseif ($pricelist == 2) {
                        $price = 'stockist';
                      }elseif ($pricelist == 2) {
                        $price = 'distributor';
                      }elseif ($pricelist == 3) {
                        $price = 'retail';
                      }else {
                        $price = 'retail';
                      }
                    }

                      $sql="SELECT * FROM products WHERE id IN (";

                              foreach($_SESSION['cart'] as $id => $value) {
                                  $sql.=$id.",";
                              }

                              $sql=substr($sql, 0, -1).") ORDER BY name ASC";
                              $query=$conn->query($sql);
                              $totalprice=0;
                              while($row=$query->fetch_assoc()){
                                if (isset($_GET['customer'])) {
                                  $cid = $_GET['customer'];
                                  $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE customers.id='$cid'") or die($conn->error);
                                  $array = $cust->fetch_array();
                                  $pricelist = $array['pricelist'];
                                  if ($pricelist==1) {
                                    $subtotal = $_SESSION['cart'][$row['id']]['quantity']*$row['wholesale'];
                                    $totalprice += $subtotal;
                                  }elseif ($pricelist == 2) {
                                    $subtotal = $_SESSION['cart'][$row['id']]['quantity']*$row['stockist'];
                                    $totalprice += $subtotal;
                                  }elseif ($pricelist == 3) {
                                    $subtotal = $_SESSION['cart'][$row['id']]['quantity']*$row['distributor'];
                                    $totalprice += $subtotal;
                                  }elseif ($pricelist == 4) {
                                    $subtotal = $_SESSION['cart'][$row['id']]['quantity']*$row['retail'];
                                    $totalprice += $subtotal;
                                  }else {
                                    $subtotal = $_SESSION['cart'][$row['id']]['quantity']*$row['retail'];
                                    $totalprice += $subtotal;
                                  }
                                }

                              ?>
                                  <tr>
                                      <td><?php echo $row['name']; ?></td>
                                      <td><input type="text" name="quantity[<?php echo $row['id'] ?>]" size="5" value="<?php echo $_SESSION['cart'][$row['id']]['quantity'] ?>" /></td>
                                      <?php
                                      if (isset($_GET['customer'])) {
                                        $cid = $_GET['customer'];
                                        if ($cid =="-1") {
                                          echo '<script>window.location.href="sales_create.php"</script>';
                                        }
                                        $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE customers.id='$cid'") or die($conn->error);
                                        $array = $cust->fetch_array();
                                        $pricelist = $array['pricelist'];
                                        if ($pricelist==1) {
                                        echo "<td>Kshs. ".$row['wholesale']."</td>";
                                        echo "<td>Kshs.".$_SESSION['cart'][$row['id']]['quantity']*$row['wholesale']."</td>";
                                        }elseif ($pricelist == 2) {
                                          echo "<td>Kshs.". $row['stockist']."</td>";
                                          echo "<td>Kshs.".$_SESSION['cart'][$row['id']]['quantity']*$row['stockist']."</td>";
                                        }elseif ($pricelist == 3) {
                                          echo "<td>Kshs. ". $row['distributor']."</td>";
                                          echo "<td>Kshs.".$_SESSION['cart'][$row['id']]['quantity']*$row['distributor']."</td>";

                                        }elseif ($pricelist == 4) {
                                          echo "<td>Kshs. ".$row['retail']."</td>";
                                          echo "<td>Kshs.".$_SESSION['cart'][$row['id']]['quantity']*$row['retail']."</td>";

                                        }else {
                                          echo "<td>Kshs. ".$row['retail']."</td>";
                                          echo "<td>Kshs.".$_SESSION['cart'][$row['id']]['quantity']*$row['retail']."</td>";
                                        }
                                      }else{
                                        echo "<td>Kshs. ".$row['retail']."</td>";
                                        echo "<td>Kshs.".$_SESSION['cart'][$row['id']]['quantity']*$row['retail']."</td>";
                                      }
                                       ?>
                                      <td><a href="sales_create.php?page=products&action=remove&id=<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></a></td>
                                  </tr>
                              <?php }?>
                              <tr><td colspan="3"></td>
                                  <th>Total Price: <?php echo $totalprice ?></th>
                                  <td>&nbsp;</td>

                              </tr>

                              <tr><td colspan="3"></td>
                                  <th>Discount: <span id="discount"></span></th>
                                  <td>&nbsp;</td>
                              </tr>
                            <?php } ?>
                            </table>

                            <div class="row col-md-12">
                              <div class="col-md-1">
                                &nbsp;
                              </div>
                              <div class="col-md-3">
                                <label for="">Payment Type: </label>
                                <select class="form-control" name="payment_type">
                                  <option value="">Select an option</option>
                                  <option value="mpesa">Mpesa</option>
                                  <option value="cheque">Cheque</option>
                                </select>
                                <span style="color: red;"><?php echo $type_err; ?></span>
                              </div>
                              <div class="col-md-3">
                                <label for="">Confirmation Code</label>
                                <input type="text" name="c_code" value=""placeholder="Code received" class="form-control" >
                                <span style="color: red;"><?php echo $code_err; ?></span>
                              </div>
                              <div class="col-md-3">
                                <label for="">Amount Paid</label>
                                <input type="text" name="amount" value="" class="form-control" placeholder="Amount paid in KES e.g. 20000">
                                <span style="color: red;"><?php echo $amt_err; ?></span>
                              </div>
                            </div>
                            <div class="row col-md-12">&nbsp;</div>

                            <center><button type="submit" name="update_cart" class="btn btn-primary" style="margin-left: 4em;">Update Catalog</button></center>
                          </form>

                          <button type="submit" name="clear_cart" class="btn btn-primary" style="margin-left: 2em;">Clear</button>
                          <button type="submit" name="save_sale" class="btn btn-primary" style="margin-left: 2em;">Save Sale</button>
                          <button type="submit" name="save_print" class="btn btn-primary" style="margin-left: 2em;">Save Sale & Print</button>


                  </div>

                  <hr>
                <!-- /.card-body -->
              </div>
            </form>
          </div>
        </div>
      </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

      <footer class="main-footer">
      <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="#">Premiersoft Technologies Ltd</a>.</strong> All rights reserved.

        <div class="float-right d-none d-sm-inline-block">
          <b>Version</b> 1.0.2
        </div>
      </footer>
      <!-- Control Sidebar -->
      <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
      </aside>
      <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

	<!-- Jquery Core Js -->
    <script src="js/jquery.min.js"></script>



    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

	<script>

	$(document).ready(function(e){

		$("#suggestion_textbox").autocomplete({
			source:'search.php'
		});

    $('#suggestion_textbox').on('keypress', function(e) {
      var code = e.keyCode || e.which;
      if(code==13){
          e.preventDefault();
          let name = $("#suggestion_textbox").val();
          window.location.href = "sales_create.php?page=products&action=add&name="+name;
      }
  });

	});
	</script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../dist/js/demo.js"></script>
  </body>
  </html>
