<?php include '../utils/conn.php'; ?>
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
<!--head-->
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
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Expenses</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Expenses</a></li>
              <li class="breadcrumb-item active">List</li>
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
                <h3 class="card-title">All Expenses</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="location_id">Business Location:</label>
                      <select class="form-control" name="">
                        <option value="">All</option>
                        <?php
                        $stores = $conn->query("SELECT * FROM stores") or die($conn->error);
                        while ($row=$stores->fetch_assoc()) {
                          echo "<option value=".$row['id'].">".$row['name']."</option>";
                        }
                         ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="expense_for">Expense for:</label>
                      <select class="form-control" name="user">
                        <option value="">All</option>
                        <?php
                        $users = $conn->query("SELECT id, name FROM users") or die($conn->error);
                        while ($row = $users->fetch_assoc()) {
                          echo "<option value=".$row['id'].">".$row['name']."</option>";
                        }
                         ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="expense_category_id">Expense Category:</label>
                      <select class="form-control" name="">
                        <option value="">All</option>
                        <?php
                        $stores = $conn->query("SELECT * FROM expense_categories") or die($conn->error);
                        while ($row=$stores->fetch_assoc()) {
                          echo "<option value=".$row['id'].">".$row['name']."</option>";
                        }
                         ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="expense_payment_status">Payment Status:</label>
                        <select class="form-control select2" style="width:100%" id="expense_payment_status" name="expense_payment_status"><option selected="selected" value="">All</option><option value="paid">Paid</option><option value="due">Due</option><option value="partial">Partial</option></select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                        <label for="expense_date_range">Date Range:</label>
                        <input placeholder="Select a date range" class="form-control" id="expense_date_range" readonly name="date_range" type="text">
                    </div>
                  </div>
                  <hr>
                </div>
                <hr>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th style="display: none;">Action</th>
                      <th>Date</th>
                      <th>Reference No</th>
                      <th>Expense Category</th>
                      <th>Location</th>
                      <th>Payment status</th>
                      <th>Tax</th>
                      <th>Total amount</th>
                      <th>Expense for</th>
                      <th>Expense note</th>
                      <th>Added By</th>
                  </tr>
                  </thead>
                  <!-- ``, `refno`, `date`, `user`, `note`, `tax`, `amount`, `type`, `account` -->
                  <tbody>
                    <?php
                    $query = "SELECT * FROM accounts";
                    $res = $conn->query($query) or die($conn->error);
                    while ($row = $res->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>".$row['date']."</td>";
                      echo "<td>".$row['refno']."</td>";
                      echo "<td>".$row['category']."</td>";
                      echo "<td>Central</td>";
                      echo "<td><span class='bg-danger'>pending</span></td>";
                      echo "<td>".$row['tax']."</td>";
                      echo "<td>".$row['amount']."</td>";
                      echo "<td>".$row['user']."</td>";
                      echo "<td>".$row['note']."</td>";
                      echo "<td> Admin</td>";
                      echo "</tr>";
                    }
                     ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th style="display: none;">Action</th>
                      <th>Date</th>
                      <th>Reference No</th>
                      <th>Expense Category</th>
                      <th>Location</th>
                      <th>Payment status</th>
                      <th>Tax</th>
                      <th>Total amount</th>
                      <th>Expense for</th>
                      <th>Expense note</th>
                      <th>Added By</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
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

    <!--    footer-->
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
<script src="../../plugins/moment/moment.min.js"></script>
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
</body>
</html>
