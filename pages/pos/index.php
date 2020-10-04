<?php include 'process.php';
session_start();
if(isset($_POST['update_cart'])){
foreach($_POST['quantity'] as $key => $val) {
    if($val==0) {
        unset($_SESSION['cart'][$key]);
    }else{
        $_SESSION['cart'][$key]['quantity']=$val;
    }
}
header('location: index.php');
}

if(isset($_POST['clear_cart'])){
  unset($_SESSION['cart']);
  header('location: index.php');
}

if(isset($_POST['save_sale'])){
  $doc_number = $_POST['doc_number'];
  $customer = $_POST['customer_name'];
  $s = $_POST['salesman'];
  $user = $conn->query("SELECT id FROM users WHERE username='$s'") or die($conn->error);
  $user_date = $user->fetch_array();
  $user = $user_date['id'];

  $sql="SELECT * FROM products WHERE id IN (";

          foreach($_SESSION['cart'] as $id => $value) {
              $sql.=$id.",";
          }

          $sql=substr($sql, 0, -1).") ORDER BY name ASC";
          $query=$conn->query($sql);
          $totalprice = 0;
          while($row=$query->fetch_assoc()){
              $subtotal = $_SESSION['cart'][$row['id']]['quantity']*$row['price'];
              $totalprice += $subtotal;
              $id= $row['id'];
              $quantity = $_SESSION['cart'][$row['id']]['quantity'];
              $conn->query("INSERT INTO `new_sale`(`product`, `quantity`,`doc_number`, `salesman`) VALUES ('$id','$quantity','$doc_number','$user')") or die($conn->error);
            }
  unset($_SESSION['cart']);
  header('location: index.php');

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
        header('location: index.php');
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
        <a href="index.php" class="nav-link">Home</a>
      </li>
      <!--<li class="nav-item d-none d-sm-inline-block">-->
      <!--  <a href="#" class="nav-link">Contact</a>-->
      <!--</li>-->
    </ul>

    <!-- SEARCH FORM -->
    <!--<form class="form-inline ml-3">-->
    <!--  <div class="input-group input-group-sm">-->
    <!--    <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">-->
    <!--    <div class="input-group-append">-->
    <!--      <button class="btn btn-navbar" type="submit">-->
    <!--        <i class="fas fa-search"></i>-->
    <!--      </button>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</form>-->

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

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item has-treeview">
             <a href="index.php" class="nav-link active">
               <i class="nav-icon fas fa-tachometer-alt"></i>
               <p>
                 Dashboard
               </p>
             </a>
           </li>
           <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                User Management
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../users/users.php" class="nav-link">
                  <i class="fas fa-users nav-icon"></i>
                  <p>Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../users/groups.php" class="nav-link">
                  <i class="fas fa-briefcase nav-icon"></i>
                  <p>Roles</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item has-treeview">
           <a href="#" class="nav-link">
             <i class="nav-icon fas fa-address-book"></i>
             <p>
               Contacts
               <i class="fas fa-angle-left right"></i>
             </p>
           </a>
           <ul class="nav nav-treeview">
             <li class="nav-item">
               <a href="../contacts/suppliers.php" class="nav-link">
                 <i class="fas fa-star nav-icon"></i>
                 <p>Suppliers</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="../contacts/customers.php" class="nav-link">
                 <i class="fas fa-star nav-icon"></i>
                 <p>Customers</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="../contacts/groups.php" class="nav-link">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Customer Groups</p>
               </a>
             </li>
           </ul>
         </li>
         <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cubes"></i>
            <p>
              Products
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
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
            <li class="nav-item">
              <a href="../products/categories.php" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>Product Categories</p>
              </a>
            </li>
          </ul>
        </li>
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
       <li class="nav-item has-treeview">
        <a href="#" class="nav-link">
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
          <li class="nav-item">
            <a href="../sales/sales_create.php" class="nav-link">
              <i class="fas fa-plus-circle nav-icon"></i>
              <p>Add Sales</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../sales/issue.php" class="nav-link">
              <i class="fas fa-truck nav-icon"></i>
              <p>Issue Stock</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../sales/request.php" class="nav-link">
              <i class="fas fa-handshake nav-icon"></i>
              <p>Request Stock</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../sales/clear.php" class="nav-link">
              <i class="fas fa-circle nav-icon"></i>
              <p>Clear Sales</p>
            </a>
          </li>
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
     <li class="nav-item has-treeview">
      <a href="#" class="nav-link">
        <i class="nav-icon fas fa-minus-circle"></i>
        <p>
          Expenses
          <i class="fas fa-angle-left right"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="../accounts/list.php" class="nav-link">
            <i class="fas fa-list nav-icon"></i>
            <p>List Expenses</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="../accounts/account_create.php" class="nav-link">
            <i class="fas fa-plus-circle nav-icon"></i>
            <p>Add Expense</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="../accounts/account_groups.php" class="nav-link">
            <i class="fas fa-plus-circle nav-icon"></i>
            <p>Expense Categories</p>
          </a>
        </li>
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
   
   
   
   
    <li class="nav-item">
      <a href="../utils/logout.php" class="nav-link">
        <i class="fas fa-undo-alt"></i>
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
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
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
          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Premier Sale</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="index.php" method="POST">
                <input type="hidden" name="salesman" value="<?php echo $username; ?>">
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Receipt Number: </label>
                        <input type="text" name="doc_number" value="DOC-SALE-<?php echo round(microtime(true)*1000); ?>" class="form-control" readonly>
                      </div>
                      <div class="col-md-4">
                        <label for="customer_name">Customer: </label>
                        <select class="form-control" name="customer_name">
                          <option value="normal-customer"> Walkin Customer</option>
                          <?php
                          $query = "SELECT id,cust_name FROM customers";
                          $res = $conn->query($query) or die($conn->error);
                          while($row = $res->fetch_assoc()){
                            echo "<option value=".$row['id']."> ".$row['cust_name']."</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label>Sale Date: </label>
                        <input type="date" name="sale_date" value="<?php echo date("Y-m-d"); ?>" class="form-control" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <div class="col-md-12">
                &nbsp;
              </div>
              <div class="col-md-4">
                &nbsp;
              </div>
              <div class="col-md-8">
                <label for="">Search For a Product:</label>
                <input class="form-control" type="text" id="suggestion_textbox" />
              </div>
              <div class="col-md-2">
                &nbsp;
              </div>
                    <div class="col-md-12">
                    <form method="post" action="index.php?page=cart">
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

                      $sql="SELECT * FROM products WHERE id IN (";

                              foreach($_SESSION['cart'] as $id => $value) {
                                  $sql.=$id.",";
                              }

                              $sql=substr($sql, 0, -1).") ORDER BY name ASC";
                              $query=$conn->query($sql);
                              $totalprice=0;
                              while($row=$query->fetch_assoc()){
                                  $subtotal = $_SESSION['cart'][$row['id']]['quantity']*$row['price'];
                                  $totalprice += $subtotal;
                              ?>
                                  <tr>
                                      <td><?php echo $row['name']; ?></td>
                                      <td><input type="text" name="quantity[<?php echo $row['id'] ?>]" size="5" value="<?php echo $_SESSION['cart'][$row['id']]['quantity'] ?>" /></td>
                                      <td>Kshs. <?php echo $row['price'] ?></td>
                                      <td>Kshs.<?php echo $_SESSION['cart'][$row['id']]['quantity']*$row['price'] ?></td>
                                      <td><a href="index.php?page=products&action=remove&id=<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></a></td>
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
                          </div>
                        <div class="row">
                          <div class="col-md-1">
                            &nbsp;
                          </div>
                          <div class="col-md-4">
                            <button type="submit" name="update_cart" class="btn btn-primary" style="width:300px;">Update Catalog</button>
                          </div>
                          <div class="col-md-2">
                            <button type="submit" name="clear_cart" class="btn btn-primary" style="width:100px;">Clear</button>
                          </div>
                          <div class="col-md-2">
                            <button type="submit" name="save_sale" class="btn btn-primary" style="width:100px;">Save Sale</button>
                          </div>
                          <div class="col-md-2">
                            <button type="submit" name="save_print" class="btn btn-primary" style="width:200px;">Save Sale & Print</button>
                          </div>
                        </div>
                        <div class="row">
                          &nbsp;
                        </div>


                      </form>
                  </div>
            </div><!-- /.container-fluid -->
          </section>
          <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- /.content-wrapper -->
      <footer class="main-footer">
        <strong>Copyright &copy; <?php echo date("Y"); ?> <a href="https://premiersoft.co.ke" target="_blank">Premiersoft Systems</a>.</strong> All rights reserved.

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
          window.location.href = "index.php?page=products&action=add&name="+name;
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
