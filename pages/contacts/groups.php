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
    <!-- /.content-header -->
    <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Customer Group</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="" method="POST">
                <div class="card-body">
                  <span style="color: red;">Sections marked with * must be filled</span>
                  <input type="hidden" name="id" value="">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Group Name<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="name" id="exampleInputEmail1" placeholder="Enter name" value="">
                    <span style="color: red;"><?php echo $name_err; ?></span>
                  </div>
                  <div class="form-group" id="bottlesnumber">
                    <label for="shellcost">Calculation Percentage<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="" name="percentage" placeholder="%" value="">
                    <span style="color: red;"><?php echo $percentage_err; ?></span>
                  </div>
                  <div class="form-group">
                    <label for="shellcost">Price List<span style="color: red;">*</span></label>
                    <select class="form-control" name="price_list">
                       <?php
                        $res = $conn->query("SELECT id, name FROM pricelist order by name") or die($conn->error);
                        while ($row = $res->fetch_assoc()) {
                          echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                        }
                         ?>
                    </select>
                    <span style="color: red;"><?php echo $price_list_err; ?></span>
                  </div>
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary" name="groups_submit">Submit</button>
                  </div>
              </div>
            </form>
          </div>
        </div>
            <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Registered Customer Groups</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Calculation Percentage</th>
                      <th>Price Lists</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $res = $conn->query("SELECT cust_groups.id,cust_groups.name as gname,cust_groups.percentage, pricelist.name as pname FROM cust_groups LEFT JOIN pricelist ON cust_groups.pricelist=pricelist.id ORDER BY cust_groups.name") or die($conn->error);
                  while ($row = $res->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['gname']."</td>";
                    echo "<td>".$row['percentage']."</td>";
                    echo "<td>".$row['pname']."</td>";
                    echo "<td>&nbsp;</td>";
                    }
                    echo "</tr>";
                  
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

          </div>
          </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
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

<?php
    include __DIR__.'/../partials/scripts.php';
?>
  </body>
  </html>
