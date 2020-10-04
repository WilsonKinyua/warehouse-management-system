<?php include 'process.php';
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
    <div class="content-header">
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Security Groups</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
          <div class="card-body" style="display: block;">
            <div class="row">
              <div class="col-md-6">
                <label>Group Name </label>
              </div>
              <div class="col-md-6">
                <input type="text" name="name" value="" class="form-control" placeholder="Enter Group name e.g. Workshop Accountants"/>
                <span style="color: red;"><?php echo $group_name_err; ?></span>
              </div>
              <div class="col-md-12">
                  <p></p>
              </div>
                <div class="col-md-3">
                 <!-- checkbox -->
                  <div class="form-group">
                    <label>Inventory</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_categories"><label>Manage Categories</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_products"><label>Manage Products</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_suppliers"><label>Manage Suppliers</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                 <!-- checkbox -->
                  <div class="form-group">
                    <label>Stocks</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="receive_stock"><label>Receive Stock</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="transfer_stock"><label>Transfer Stock</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="issue_stock"><label>Issue Stock</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="request_stock"><label>Request Stock</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                 <!-- checkbox -->
                  <div class="form-group">
                    <label>Sales</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="make_sales"><label>Make Sales</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="clear_sales"><label>Clear Sales</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="credit_sales"><label>Make Credit Sale</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="sell_from_main"><label>Sell From Main Store</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                 <!-- checkbox -->
                  <div class="form-group">
                    <label>Accounts</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_accounts"><label>Manage Accounts</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_account_categories"><label>Manage Categories</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_expenses"><label>Manage Expenses</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                 <!-- checkbox -->
                  <div class="form-group">
                    <label>Stock Reports</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="sales_reports"><label>Sales Reports</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="transfer_reports"><label>Transfer Reports</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="issue_reports"><label>Issue Stock Reports</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="recieve_reports"><label>Recieve Stock Reports</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="reports"><label>Other Reports</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                 <!-- checkbox -->
                  <div class="form-group">
                    <label>Security</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_users"><label>Manage Users</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_user_groups"><label>Manage User Groups</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_user_rights"><label>Manage User Rights</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                 <!-- checkbox -->
                  <div class="form-group">
                    <label>Customers</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_customer_groups"><label>Manage Customer Groups</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_customers"><label>Manage Customers</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_price_rules"><label>Manage Price Rules</label>
                    </div>
                  </div>
                </div>

                <div class="col-md-3">
                 <!-- checkbox -->
                  <div class="form-group" style="display: none;">
                    <label>Miscellenious</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="manage_settings"><label>Manage Settings</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="dashboard"><label>View Dashboard</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-3" style="display: none;">
                 <!-- checkbox -->
                  <div class="form-group">
                    <label>Allowed Warehouse</label>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="all"><label>All</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="nanyuki"><label>Nanyuki</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="isiolo"><label>Isiolo</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="timau"><label>Timau</label>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="card-footer">
            <button type="submit" class="btn btn-primary" name="user_groups_submit">Submit</button>
          </div>
        </form>
              </div>

              </div>
            </div>
            <!-- /.row -->

          </div>
          <!-- /.card-body -->

        </div>
                </form>
              </div>
              <!-- /.card-body -->
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
<!-- Bootstrap -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI -->
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- fullCalendar 2.2.5 -->

<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- Page specific script -->
<script type="text/javascript">
$(function () {
  $("#example1").DataTable();
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
  });
});
$(function(){
    var current = '../users/groups.php';
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
