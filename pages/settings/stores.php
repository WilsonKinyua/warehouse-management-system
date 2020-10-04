<?php
include 'process.php';

?>
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
<!-- head -->
<?php include __DIR__.'/../partials/head.php'; ?>
<!-- /.head -->
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
              <li class="breadcrumb-item active">Tax Rates</li>
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
                <h3 class="card-title">Add a Store</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="card-body">
                  <span style="color: red;">Sections marked with * must be filled</span>
                  <input type="hidden" name="id" value="<?php echo $id; ?>">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Name: <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="store_name" placeholder="Enter a name e.g. Van II" value="<?php echo $term_name; ?>">
                    <span style="color: red;"><?php echo $store_name_err; ?></span>
                  </div>
                  <div class="form-group" id="costofshells">
                    <input type="checkbox" name="is_mobile" value=""> &nbsp;Is a mobile store
                  </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <?php if ($update) :?>
                  <button type="submit" class="btn btn-primary" name="stores_update">Update</button>
                  <?php else:?>
                  <button type="submit" class="btn btn-primary" name="stores_submit">Submit</button>
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
                <h3 class="card-title">All Pay Terms</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Store Type</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $number =1;
                    $res = $conn->query("SELECT * FROM stores") or die($conn->error);
                    while ($row= $res->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?php echo $number; ?></td>
                      <td><?php echo $row['name']; ?></td>
                      <?php if($row['is_mobile']== 1) {
                        echo "<td>Mobile Store</td>";
                      }else {
                        echo "<td>Warehouse</td>";
                      }
                      ?>
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
<!-- /.scripts -->

</body>
</html>
