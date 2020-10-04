<?php
include '../utils/conn.php';
include 'process.php'; ?>
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
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Settings</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Settings</a></li>
              <li class="bread menu-opencrumb-item active">Discounts</li>
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
                <h3 class="card-title">Add A Discount</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="card-body">
                  <span style="color: red;">Sections marked with * must be filled</span>
                  <input type="hidden" name="id" value="<?php echo $id; ?>">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name: <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="name" placeholder="Enter a name e.g. Tax @ 10%" value="<?php echo $name; ?>">
                    <span style="color: red;"><?php echo $discount_name_err; ?></span>
                  </div>
                  <div class="form-group" id="costofshells">
                    <label for="discount_rate">Discount Rate %:<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="rate" placeholder="Enter a rate e.g. 10" value="<?php echo $rate; ?>">
                    <span style="color: red;"><?php echo $discount_rate_err; ?></span>
                  </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <?php if ($update) :?>
                  <button type="submit" class="btn btn-primary" name="discount_update">Update</button>
                  <?php else:?>
                  <button type="submit" class="btn btn-primary" name="discount_submit">Submit</button>
                <?php endif;?>
                </div>
              </div>
            </form>
          </div>
        </div>
            <!-- /.card -->
            <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Tax Rates</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Tax Rates %</th>
                      <th colspan="2">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $number =1;
                    $res = $conn->query("SELECT * FROM discounts") or die($conn->error);
                    while ($row= $res->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?php echo $number; ?></td>
                      <td><?php echo $row['name']; ?></td>
                      <td><?php echo $row['discount_rate']."%";?></td>
                      <td style="width: 40px; display:none;"><a href="discounts.php?discounts_edit=<?php echo $row['id'];?>"><button class="btn btn-success">Edit</button></a></td>
                      <td style="width: 40px: display: none;"><a href="discounts.php?discounts_delete=<?php echo $row['id'];?>"><button class="btn btn-danger">Delete</button></a></td>

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

    <!-- footer -->
    <?php include __DIR__.'/../partials/footer.php'; ?>
    <!-- /.footer -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- scripts -->
<?php include __DIR__.'/../partials/scripts.php'; ?>
<!--    end scripts-->
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/fullcalendar/main.min.js"></script>
<script src="../../plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="../../plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="../../plugins/fullcalendar-interaction/main.min.js"></script>
<script src="../../plugins/fullcalendar-bootstrap/main.min.js"></script>
<!-- Page specific script -->
<script type="text/javascript">
  $(document).ready(function(){
  $("#emptiesqn").change(function(){
    if ($(this).val()=='1') {
      $("#costofshells").show();
      $("#bottlesnumber").show();
      $("#bottlescost").show();
    }
    else{
      $("#costofshells").hide();
      $("#bottlesnumber").hide();
      $("#bottlescost").hide();
    }
  });
});
</script>
</body>
</html>
