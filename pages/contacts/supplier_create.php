<?php include 'process.php'; ?>
<?php
session_start();
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
    <!-- Content Header (Page header) -->
    <!--<div class="content-header">-->
    <!--  <div class="container-fluid">-->
    <!--    <div class="row mb-2">-->
    <!--      <div class="col-sm-6">-->
    <!--        <h1 class="m-0 text-dark">Dashboard</h1>-->
    <!--      </div><!-- /.col -->
    <!--      <div class="col-sm-6">-->
    <!--        <ol class="breadcrumb float-sm-right">-->
    <!--          <li class="breadcrumb-item"><a href="#">Home</a></li>-->
    <!--          <li class="breadcrumb-item active">Dashboard v1</li>-->
    <!--        </ol>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</div>-->
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add A Supplier</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="card-body">
                  <span style="color: red;">Sections marked with * must be filled</span>
                  <input type="hidden" name="id" value="<?php echo $id; ?>">
                  <div class="row">
                      <div class="col-md-4">
                        <label for="">Name : <span style="color: red;">*</span></label>
                        <input type="text" name="name" value="" placeholder="Business name" class="form-control">
                      </div>
                    <div class="col-md-4">
                      <label for="">Contact: <span style="color: red;">*</span></label>
                      <input type="text" name="contact" value="" placeholder="Mobile Phone"  class="form-control">
                    </div>
                    <div class="col-md-4">
                      <label for="">KRA PIN: <span style="color: red;">*</span></label>
                      <input type="text" name="refno" class="form-control" placeholder="KRA PIN" required/>
                    </div>
                    <div class="col-md-4">
                      <label for="">Email: </label>
                      <input type="email" name="email" class="form-control" placeholder="Email eg eabl@eabl.com" required/>
                    </div>
                    <hr>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-md-4">
                      <label for="">Opening Balance: <span style="color: red;">*</span></label>
                      <input type="text" name="opening_balance" class="form-control" value="0.00"/>
                    </div>
                    <div class="col-md-4">
                      <label for="">Pay Term: <span style="color: red;">*</span></label>
                      <select class="form-control" name="payterm">
                        <option value="">None</option>
                        <option value="7">Weekly</option>
                        <option value="30">Monthly</option>
                        <option value="90">Quarterly</option>
                      </select>
                    </div>
                  </div>
                  <hr>
                </div>
                <hr>

                <!-- /.card-body -->

                <div class="card-footer">
                  <?php if ($update) :?>
                  <button type="submit" class="btn btn-primary" name="supplier_update">Update</button>
                  <?php else:?>
                  <button type="submit" class="btn btn-primary" name="supplier_submit">Save</button>
                <?php endif;?>
                </div>
              </div>
            </form>
          </div>
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

  <!-- jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../dist/js/demo.js"></script>
    <script>
        $(function(){
            var current = '../contacts/suppliers.php';
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
  </body>
  </html>
