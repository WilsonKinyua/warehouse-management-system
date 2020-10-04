<?php
session_start();
include 'process.php';

$sm = isset($_SESSION['sm'])? $_SESSION['sm'] :"";
if(isset($_POST['update_cart'])){
foreach($_POST['quantity'] as $key => $val) {
    if($val==0) {
        unset($_SESSION['assign_cart'][$key]);
    }else{
        $_SESSION['assign_cart'][$key]['quantity']=$val;
    }
}
    header('location: request.php?salesman='.$sm);
}

if(isset($_POST['clear_cart'])){
  unset($_SESSION['assign_cart'], $_SESSION['sm']);
  header('location: request.php');
}

$salesman_err = "";
if(isset($_POST['save_asign'])){

    $doc_number = $_POST['doc_number'];
    $salesman = $_POST['salesman_name'];
    $date = date('Y-m-d');

    // $s = $_POST['salesman'];
    // $user = $conn->query("SELECT id FROM users WHERE username='$s'") or die($conn->error);
    // $user_date = $user->fetch_array();
    // $user = $user_date['id'];
    if (empty($salesman)) {
      $salesman_err = "Please select a salesman";
      header('location: request.php');
    }
    // var_dump($_POST);
    $sql="SELECT * FROM products WHERE id IN (";

          foreach($_SESSION['assign_cart'] as $id => $value) {
              $sql.=$id.",";
          }

          $sql=substr($sql, 0, -1).") ORDER BY name ASC";
          $query=$conn->query($sql);
          $totalprice = 0;

          while($row=$query->fetch_assoc()){

              $subtotal = $_SESSION['assign_cart'][$row['id']]['quantity']*$row['price'];
              $totalprice += $subtotal;
              $id= $row['id'];
              $quantity = $_SESSION['assign_cart'][$row['id']]['quantity'];
           
              //Check if the product exists
                $rsql = $conn->query("SELECT * FROM products WHERE id='$id'") or die($conn->error);
                $nsql = $rsql->fetch_array();
                $nprod = $nsql['name'];
                $res = $conn->query("SELECT name, quantity_available FROM stock INNER JOIN products ON stock.product = products.id WHERE product='$id'") or die($conn->error);
                
                if (mysqli_num_rows($res) > 0) {

                    // Update Stock
                    $row = $res->fetch_array();
                    $item = $row['name'];
                    $avstock = $row['quantity_available'];

                    if ($row['quantity_available'] < $quantity) {

                      // echo "<script>alert('Entered Quantity Above available stock')</script>";   
                    //   echo "<script type='text/javascript'>alert('$item Requested Quantity $quantity Above available stock $avstock'); </script>";
                      setcookie("quantity_available_exceeded", $item.' Requested Quantity '.$quantity.' Above available stock '.$avstock, time()+3);

                    } else {
                        
                      $qty_a = $row['quantity_available'];
                      $qty_a -= $quantity;
                      $conn->query("UPDATE `stock` SET `quantity_available`='$qty_a' WHERE product='$id'") or die($conn->error);

                      // Check if this product exists in Employees Wallet
                      $res1 = $conn->query("SELECT quantity_available FROM stock_mobile WHERE product='$id' AND store_owner='$salesman' AND clear_status= 0 ") or die($conn->error);
                      
                      if (mysqli_num_rows($res1) > 0) {

                        // Update Stock and Insert to Additions
                        $row1 = $res1->fetch_array();
                        $quantity0 = $quantity;
                        $quantity += $row1['quantity_available'];
                        $conn->query("UPDATE stock_mobile SET quantity_available='$quantity' WHERE product='$id' AND store_owner='$salesman' AND id IN 
                        (SELECT max(id) FROM (SELECT * from `stock_mobile`  WHERE `store_owner`='$salesman' AND `product`='$id' ) as sm GROUP By `product`)") or die($conn->error);
                        $conn->query("INSERT INTO `stock_mobile_additions`(`store_owner`, `product`, `quantity_added`) VALUES ('$salesman','$id','$quantity0')") or die($conn->error);
                        
                      } else{ 

                        $conn->query("INSERT INTO `stock_mobile`(`store_owner`, `product`, `quantity_available`) VALUES ('$salesman','$id','$quantity')") or die($conn->error);
                        $conn->query("INSERT INTO `stock_mobile_additions`(`store_owner`, `product`, `quantity_added`) VALUES ('$salesman','$id','$quantity')") or die($conn->error);
                        
                      }

                      setcookie("purchase_success", true, time()+3);
                      unset($_SESSION['sm'], $_SESSION['assign_cart']);
                  }
               
              } 

              else{
             //   echo "<script>alert('Product is out of stock')</script>";
              echo "<script type='text/javascript'>alert('$nprod is out of stock Kindly Purchase'); </script>";
              }
            }
            // echo "<script type='text/javascript'>alert('Products Issued Successfully'); </script>";
            
            header('location: request.php');

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

        if(isset($_SESSION['assign_cart'][$id])){
            $_SESSION['assign_cart'][$id]['quantity']++;
        }else{
            $sql_s="SELECT * FROM products WHERE id={$id}";
            $query_s=$conn->query($sql_s);
            if(mysqli_num_rows($query_s)!=0){
                $row_s=$query_s->fetch_array();
                $_SESSION['assign_cart'][$row_s['id']]=array(
                        "quantity" => 1,
                        "price" => $row_s['price']
                    );
            }else{
                $message="This product id it's invalid!";
            }

        }
        header('location: request.php');
    }
    if(isset($_GET['action']) && $_GET['action']=="remove"){

            $id=intval($_GET['id']);
            unset($_SESSION['assign_cart'][$id]);
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
    <!-- Content Header (Page header) 
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div>
        </div>
      </div>
    </div> -->
    <!-- /.content-header -->
    <script type="text/javascript">

      function selectSaleman(){
        var salesman = document.getElementById('salesman').value;
        window.location.href = "request.php?salesman="+salesman;
      }

    </script>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">

            <?php if(isset($_COOKIE['purchase_success']) and $_COOKIE['purchase_success']) {?>
              
              <div id="notif-" class="alert alert-success text-center alert-dismissible fade show mt-4" role="alert">
                    <strong>Stock Assigned Successfully</strong>
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

          <?php } ?>

          <?php if(isset($_COOKIE['quantity_available_exceeded']) and $_COOKIE['quantity_available_exceeded']) {?>
              
              <div id="notif" class="alert alert-danger text-center alert-dismissible fade show mt-4" role="alert">
                    <strong><?php echo $_COOKIE['quantity_available_exceeded'] ?></strong>
                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

          <?php } ?>

          <script>

              setTimeout(function() {
                let alerter = document.getElementById('notif')
                if(alerter) {
                  alerter.parentNode.removeChild(alerter);
                }
              },3000)

              setTimeout(function() {
                let alerter = document.getElementById('notif-')
                if(alerter) {
                  alerter.parentNode.removeChild(alerter);
                }
              },3000)

          </script>

          <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Issue Stock</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="request.php" method="POST">
                <input type="hidden" name="salesman" value="<?php echo $username; ?>">
                <div class="card-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Receipt Number: </label>
                        <input type="text" name="doc_number" value="DOC-SALE-<?php echo round(microtime(true)*1000); ?>" class="form-control" readonly>
                      </div>
                      <div class="col-md-4">
                        <label for="customer_name">Salesman: </label>
                        <select class="form-control" name="salesman_name" onchange="selectSaleman()" id="salesman">
                          <option value="">Select Salesman</option>
                          <?php
                          if (isset($_GET['salesman'])) {
                            $sm = $_SESSION['sm'] = $_GET['salesman'];
                          }
                          $salesmen_group = 7;
                          // TODO: Remove the hard coded salesmen group
                          $query = "SELECT id,name FROM users WHERE user_group='$salesmen_group' ORDER BY name";
                          $res = $conn->query($query) or die($conn->error);
                          while($row = $res->fetch_assoc()){
                            if ($row['id']== $_SESSION['sm']) {
                              echo "<option value=".$row['id']." selected> ".$row['name']."</option>";
                            }else{
                              echo "<option value=".$row['id']."> ".$row['name']."</option>";
                            }
                          }
                          ?>
                        </select>
                        <span style="color: red;"><?php echo $salesman_err; ?></span>
                      </div>
                      <div class="col-md-4">
                        <label>Request Date: </label>
                        <input type="date" name="sale_date" value="<?php echo date("Y-m-d"); ?>" class="form-control" readonly>
                      </div>
                      <div class="col-md-12">
                        &nbsp;
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

                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <form method="post" action="request.php?page=cart">
                      <table class="table table-bordered table-striped col-md-11" style="width: 100%; margin-left: 2em;">
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Remove</th>
                        </tr>

                  <?php
                  if (!isset($_SESSION['assign_cart']) || empty($_SESSION['assign_cart'])) {
                    echo "<td colspan='5'>No products in catalog</td>";
                  }else{

                      $sql="SELECT * FROM products WHERE id IN (";

                              foreach($_SESSION['assign_cart'] as $id => $value) {
                                  $sql.=$id.",";
                              }

                              $sql=substr($sql, 0, -1).") ORDER BY name ASC";
                              $query=$conn->query($sql);
                              $totalprice=0;
                              while($row=$query->fetch_assoc()){
                                  $subtotal = $_SESSION['assign_cart'][$row['id']]['quantity']*$row['price'];
                                  $totalprice += $subtotal;
                              ?>
                                  <tr>
                                      <td><?php echo $row['name']; ?></td>
                                      <td><input type="text" name="quantity[<?php echo $row['id'] ?>]" size="5" value="<?php echo $_SESSION['assign_cart'][$row['id']]['quantity'] ?>" /></td>
                                      <td>Kshs. <?php echo $row['price'] ?></td>
                                      <td>Kshs.<?php echo $_SESSION['assign_cart'][$row['id']]['quantity']*$row['price'] ?></td>
                                      <td><a href="request.php?page=products&action=remove&id=<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></a></td>
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
                          <button type="submit" name="save_asign" class="btn btn-primary" style="margin-left: 2em;">Save Assign</button>
                          <button type="submit" name="save_request_print" class="btn btn-primary" style="margin-left: 2em;">Save Assign & Print</button>
                  </div>

                  <div class="row">
                  <table class="table table-stripped" style="display: none;">
                    <tr>
                      <th>Product Name </th>
                      <th>Action</th>
                    </tr>

                      <?php
                      $products = $conn->query("SELECT * FROM products") or die($conn->error);
                      while ($row = $products->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo $row['name'] ?></td>
                            <td><?php echo $row['category'] ?></td>
                            <td><?php echo $row['price'] ?></td>
                            <td><a href="request.php?page=products&action=add&id=<?php echo $row['id'] ?>"><i class="fas fa-check-circle"></i></a></td>
                        </tr>
                        <?php } ?>

                  </table>

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
          window.location.href = "request.php?page=products&action=add&name="+name;
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
