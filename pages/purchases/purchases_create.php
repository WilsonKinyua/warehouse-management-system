<?php include '../utils/conn.php';
session_start();
if (isset($_GET['supplier'])) {
  $_SESSION['supplier'] = $_GET['supplier'];
}else{
}


if(isset($_POST['update_cart'])){
  foreach($_POST['quantity'] as $key => $val) {
      if($val==0) {
          unset($_SESSION['purchases_cart'][$key]);
      }else{
          $_SESSION['purchases_cart'][$key]['quantity']=$val;
      }
  }
  if (isset($_GET['supplier'])) {
    $id = $_GET['supplier'];
    header('location: purchases_create.php?supplier='.$id);
    // code...
  } else {
    //header('location: purchases_create.php');
  }
}

if(isset($_POST['clear_cart'])){
  unset($_SESSION['supplier'], $_SESSION['purchases_cart']);
  header('location: purchases_create.php');
}
$date = date('Y-m-d');
$supplier_err = "";
$valid = true;

if(isset($_POST['save_purchase'])){
  $doc_number = 'DOC-PRCHS'.round(microtime(true)*1000);;
  $supplier = $_POST['supplier'];
  if (empty($supplier)) {
    $supplier_err = "Supplier must be selected";
    $valid = false;
  }
  // $s = $_POST['salesman'];
  // $user = $conn->query("SELECT id FROM users WHERE username='$s'") or die($conn->error);
  // $user_date = $user->fetch_array();
  // $user = $user_date['id'];
// var_dump($_POST);
if ($valid) {

          $sql="SELECT * FROM products WHERE id IN (";

          foreach($_SESSION['purchases_cart'] as $id => $value) {
              $sql.=$id.",";
          }

          $sql=substr($sql, 0, -1).") ORDER BY name ASC";
          $query=$conn->query($sql);
          $totalprice = 0;

          while($row=$query->fetch_assoc()){

              $subtotal = $_SESSION['purchases_cart'][$row['id']]['quantity']*$row['price'];
              $totalprice += $subtotal;
              $id= $row['id'];
              $quantity = $_SESSION['purchases_cart'][$row['id']]['quantity'];
              $conn->query("INSERT INTO `stock_detail`(`store`,`entity`, `doc_number`, `product`, `trn_qty`, `total_amount`,`trn_type`,`bal_qty`,`date`,`audituser`) VALUES ('$supplier','$supplier','$doc_number','$id','$quantity','$subtotal','1','0','$date','$username')") or die($conn->error);
              //Check if the product exists

              $res = $conn->query("SELECT quantity_available FROM stock WHERE product='$id'") or die($conn->error);

              if (mysqli_num_rows($res) > 0) {

                // Update Stock
                $row = $res->fetch_array();
                $quantity += $row['quantity_available'];
                $conn->query("UPDATE `stock` SET `quantity_available`='$quantity' WHERE product='$id'") or die($conn->error);
                setcookie("I_RUN_HERE_1", true);

              } else {

                setcookie("I_RUN_HERE_2", true);
                $conn->query("INSERT INTO `stock`(`product`, `quantity_available`,`date`) VALUES ('$id','$quantity', CURDATE())") or die($conn->error);
                setcookie("I_RUN_HERE _OO_3", true);

              }
          }

        unset($_SESSION['supplier'], $_SESSION['purchases_cart']);
        setcookie("purchase_success", true, time()+3);
        header('location: purchases_create.php');

      }

  }


  if(isset($_POST['save_purchase_print'])){
    $doc_number = $_POST['doc_number'];
    $supplier = $_POST['supplier'];
    if (empty($supplier)) {
      $supplier_err = "Supplier must be selected";
      $valid = false;
    }
    // $s = $_POST['salesman'];
    // $user = $conn->query("SELECT id FROM users WHERE username='$s'") or die($conn->error);
    // $user_date = $user->fetch_array();
    // $user = $user_date['id'];
  // var_dump($_POST);
  if ($valid) {

    $sql="SELECT * FROM products WHERE id IN (";

    foreach($_SESSION['purchases_cart'] as $id => $value) {
        $sql.=$id.",";
    }
    
    $sql=substr($sql, 0, -1).") ORDER BY name ASC";
    $query=$conn->query($sql);

    $totalprice = 0;

    while($row=$query->fetch_assoc()){

        $subtotal = $_SESSION['purchases_cart'][$row['id']]['quantity']*$row['price'];
        $totalprice += $subtotal;
        $id= $row['id'];
        $quantity = $_SESSION['purchases_cart'][$row['id']]['quantity'];
        $conn->query("INSERT INTO `stock_detail`(`store`,`entity`, `doc_number`, `product`, `trn_qty`, `total_amount`,`trn_type`,`bal_qty`,`date`,`audituser`) VALUES ('$supplier','$supplier','$doc_number','$id','$quantity','$subtotal','1','0','$date','$username')") or die($conn->error);
        
        //Check if the product exists
        $res = $conn->query("SELECT quantity_available FROM stock WHERE product='$id'") or die($conn->error);

        if (mysqli_num_rows($res) > 0) {
            // Update Stock
            $row = $res->fetch_array();
            $quantity += $row['quantity_available'];
            $conn->query("UPDATE `stock` SET `quantity_available`='$quantity' WHERE product='$id'") or die($conn->error);
        }else{
            $conn->query("INSERT INTO `stock`(`product`, `quantity_available`) VALUES ('$id','$quantity')") or die($conn->error);
        }

    }
    unset($_SESSION['supplier'], $_SESSION['purchases_cart']);
    header('location: print.php');
    }

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

        if(isset($_SESSION['purchases_cart'][$id])){
            $_SESSION['purchases_cart'][$id]['quantity']++;
        }else{
            $sql_s="SELECT * FROM products WHERE id={$id}";
            $query_s=$conn->query($sql_s);
            if(mysqli_num_rows($query_s)!=0){
                $row_s=$query_s->fetch_array();
                $_SESSION['purchases_cart'][$row_s['id']]=array(
                        "quantity" => 1,
                        "price" => $row_s['price']
                    );
            }else{
                $message="This product id it's invalid!";
            }

        }
        if (isset($_GET['supplier'])) {
          $id = $_GET['supplier'];
          header('location: purchases_create.php?supplier="$id"');
        }else{

        }
    }
    if(isset($_GET['action']) && $_GET['action']=="remove"){

            $id=intval($_GET['id']);
            $_SESSION['purchases_cart'][$id]['quantity'] = 0;
            unset($_SESSION['purchases_cart'][$id]);
            unset($_SESSION['purchases_cart'][$id]['quantity']);
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

if (!isset($_SESSION['group'])) {
  header('location: ../utils/logout.php');
}else{
  $username = $_SESSION['username'];
  $group = $_SESSION['group'];
}
 ?>
<!DOCTYPE  html>
<html>
<?php
include __DIR__.'/../partials/head.php';
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <?php include __DIR__.'/../partials/navbar.php'; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include __DIR__.'/../partials/sidebar.php'; ?>
    <!--    end sidebar-->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <script type="text/javascript">
      function changeSupplier(){
        var supplier = document.getElementById('supplier').value;
        window.location.href = "purchases_create.php?supplier="+supplier;
      }
    </script>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

        <?php if(isset($_COOKIE['purchase_success']) and $_COOKIE['purchase_success']) {?>
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12 pt-4 text-center">                    
                    <div id="notif" class="alert alert-success text-center alert-dismissible fade show mt-4" role="alert">
                      <strong>Purchase Made Successfully</strong>
                      <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                    
                  </div>
                </div>

            </div>

        <?php } ?>

        <script>
              setTimeout(function() {
                let alerter = document.getElementById('notif')
                if(alerter) {
                  alerter.parentNode.removeChild(alerter);
                }
              }, 3000)

          </script>

          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Purchase</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="purchases_create.php" method="POST">
                <input type="hidden" name="salesman" value="<?php //echo $username; ?>">
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Receipt Number: </label>
                        <input type="text" name="doc_number" value="GRN-<?php echo round(microtime(true)*1000); ?>" class="form-control" readonly>
                      </div>
                      <div class="col-md-4">
                        <label for="customer_name">Supplier: </label>
                        <select class="form-control" name="supplier" id="supplier" onchange="changeSupplier()">
                          <option value="">Select Supplier</option>
                          <?php
                          $query = "SELECT id,name FROM supplier ORDER BY name";
                          $res = $conn->query($query) or die($conn->error);
                          while($row = $res->fetch_assoc()){
                            echo "<script>console.log(".$_SESSION['supplier'].")</script>";
                            if ($row['id'] == $_SESSION['supplier']) {

                              echo "<option value=".$row['id']." selected> ".$row['name']."</option>";
                            }else{
                              echo "<option value=".$row['id']."> ".$row['name']."</option>";
                            }
                          }
                          ?>
                        </select>
                        <span style="color:red;"><?php echo $supplier_err; ?></span>
                      </div>
                      <div class="col-md-4">
                        <label>Sale Date: </label>
                        <input type="date" name="sale_date" value="<?php echo date("Y-m-d"); ?>" class="form-control" readonly>
                      </div>
                      <div class="col-md-12">
                        &nbsp;
                      </div>
                      <div class="col-md-2">
                        &nbsp;
                      </div>
                      <div class="col-md-8">
                        <!-- <label>Search for A Product:</label> -->
                        <input class="form-control" type="text" placeholder="Search for A Product" id="suggestion_textbox" />
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
                              <?php if (isset($_GET['supplier'])) {  ?>
                                window.location.href = "purchases_create.php?page=products&action=add&name="+name+"&supplier="<?php echo $_GET['supplier']; ?>;
                              <?} else{?>
                              window.location.href = "purchases_create.php?page=products&action=add&name="+name;
                              <?php } ?>
                          }
                      });
                      	</script>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <form method="post" action="purchases_create.php?page=cart">
                      <table class="table table-bordered table-striped col-md-11" style="width: 100%; margin-left: 2em;">
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Remove</th>
                        </tr>

                  <?php
                  if (!isset($_SESSION['purchases_cart']) || empty($_SESSION['purchases_cart'])) {
                    echo "<td colspan='5'>No products in catalog</td>";
                  }else{

                      $sql="SELECT * FROM products WHERE id IN (";

                              foreach($_SESSION['purchases_cart'] as $id => $value) {
                                  $sql.=$id.",";
                              }

                              $sql=substr($sql, 0, -1).") ORDER BY name ASC";
                              $query=$conn->query($sql);
                              $totalprice=0;
                              while($row=$query->fetch_assoc()){
                                  $subtotal = $_SESSION['purchases_cart'][$row['id']]['quantity']*$row['price'];
                                  $totalprice += $subtotal;
                              ?>
                                  <tr>
                                      <td><?php echo $row['name']; ?></td>
                                      <td><input type="text" name="quantity[<?php echo $row['id'] ?>]" size="5" value="<?php echo $_SESSION['purchases_cart'][$row['id']]['quantity'] ?>" /></td>
                                      <td>Kshs. <?php echo $row['price'] ?></td>
                                      <td>Kshs.<?php echo $_SESSION['purchases_cart'][$row['id']]['quantity']*$row['price'] ?></td>
                                      <td><a href="purchases_create.php?page=products&action=remove&id=<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></a></td>
                                  </tr>
                              <?php }?>
                              <tr><td colspan="3"></td>
                                  <th>Total Price: <?php echo $totalprice ?></th>
                                  <td>&nbsp;</td>
                              </tr>
                            <?php } ?>
                            </table>
                            <center><button type="submit" name="update_cart" class="btn btn-primary" style="margin-left: 4em;">Update Catalog</button></center>
                          </form>
                          <button type="submit" name="clear_cart" class="btn btn-primary" style="margin-left: 2em;">Clear</button>
                          <button type="submit" name="save_purchase" class="btn btn-primary" style="margin-left: 2em;">Save Purchase</button>
                          <button type="submit" name="save_purchase_print" class="btn btn-primary" style="margin-left: 2em;">Save Purchase & Print</button>
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

<?php
include __DIR__.'/../partials/footer.php';
?>
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
          window.location.href = "purchases_create.php?page=products&action=add&name="+name;
      }
  });

	});
    $(function(){
        var current = location.pathname.replace(/\/[A-Z1-9-+]+\/[A-Z1-9-+]+\//i, '../');
        console.log(current)
        $('.nav li a').each(function(){
            var $this = $(this);
            // if the current path is like this link, make it active
            if($this.attr('href').indexOf(current) !== -1){
                $this.addClass('active');
                $this.parents('.nav-treeview').prev().addClass('active').parent().addClass('menu-open');
            }
        })
    })
	</script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../dist/js/demo.js"></script>
  </body>
  </html>
