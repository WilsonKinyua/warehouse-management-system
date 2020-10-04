<?php include 'process.php'; ?>
<?php
session_start();
if (!isset($_SESSION['group'])) {
  header('location: sections/utils/logout.php');
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
    <!-- Content Header (Page header) -->
  
    <!-- /.content-header -->
    <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Product</h3>
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
                    <label for="shellcost">Cost Price <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Cost of liquid i.e. exclude empties price" name="cost" value="<?php echo $cost; ?>">
                    <span style="color: red;"><?php echo $liquid_cost_err; ?></span>
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Retail Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="retail_cost" value="<?php echo $retail_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Stockist Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="stockist_cost" value="<?php echo $stockist_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Wholesale Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="wholesale_cost" value="<?php echo $wholesale_cost; ?>">
                  </div>
                  <!--<div class="col-md-4 form-group">-->
                  <!--  <label for="shellcost">Special Customers (1) Price<span style="color: red;">*</span></label>-->
                  <!--  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="distributor_cost" value="<?php echo $distributor_cost; ?>">-->
                  <!--</div>-->
                  <!--<div class="col-md-4 form-group">-->
                  <!--  <label for="shellcost">Special Customers (2) Price<span style="color: red;">*</span></label>-->
                    <!--<input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="retail_cost" value=" <?php echo $retail_cost; ?>">-->
                  <!--</div>-->
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Store: <span style="color: red;">*</span></label>
                    <select class="form-control" name="store">
                      <option value="">Select Store</option>
                                  <?php
                      $res1 = $conn->query("SELECT id, name FROM stores") or die($conn->error);
                      while ($str = $res1->fetch_assoc()) {
                        echo "<option value='".$str['id']."'>".$str['name']."</option>";
                      }
                       ?>
                    </select>
                  </div>
                  <!--<div class="col-md-4 form-group">-->
                  <!--  <label for="shellcost">Supplier: <span style="color: red;">*</span></label>-->
                  <!--  <select class="form-control" name="supplier">-->
                  <!--    <option value="">Select a Supplier</option> 
                //    <?php
                //   <!--    $res = $conn->query("SELECT id, name FROM supplier") or die($conn->error);-->
                //   <!--    while ($row = $res->fetch_assoc()) {-->
                //   <!--      echo "<option value='".$row['id']."'>".$row['name']."</option>";-->
               //   } 
                 //    ?> 
                  <!--  </select>-->
                  <!--</div>-->
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
            </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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

  <!-- jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
    $(function(){
        var current = location.pathname.replace(/\/[A-Z1-9-+]+\/[A-Z1-9-+]+\//i, '../');
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
  <!-- ChartJS -->
  <script src="../../plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="../../plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="../../plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="../../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="../../plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="../../plugins/moment/moment.min.js"></script>
  <script src="../../plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="../../plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="../../dist/js/../dashboard.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../dist/js/demo.js"></script>
  </body>
  </html>
