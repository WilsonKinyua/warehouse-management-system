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


<!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add Product Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="card-body">
                  <span style="color: red;">Sections marked with * must be filled</span>
                  <input type="hidden" name="id" value="<?php echo $id; ?>">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Category Name<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" name="name" id="exampleInputEmail1" placeholder="Enter name" value="<?php echo $name; ?>">
                    <span style="color: red;"><?php echo $category_err; ?></span>
                  </div>
                  <div class="form-group" >
                    <label for="emptiesqn">Does this product have empties<span style="color: red;">*</span></label>
                    <select class="form-control" id="emptiesqn" name="option">
                      <option value="1">Yes</option>
                      <option value="2">No</option>
                    </select>
                  </div>
                  <div class="form-group" id="costofshells">
                    <label for="shellcost">Cost of Shell<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="" name="shell_cost" placeholder="Cost per shell" value="<?php echo $shell_cost; ?>">
                    <span style="color: red;"><?php echo $shells_err; ?></span>
                  </div>
                  <div class="form-group" id="bottlesnumber">
                    <label for="shellcost">Number of Bottles per Shell<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="" name="bottles_number" placeholder="Bottles number in a crate" value="<?php echo $bottles_number; ?>">
                    <span style="color: red;"><?php echo $bottles_err; ?></span>
                  </div>
                  <div class="form-group" id="bottlescost">
                    <label for="shellcost">Cost of Bottle<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="" name ="bottles_price" placeholder="Cost of one Bottles" value="<?php echo $bottles_price; ?>">
                    <span style="color: red;"><?php echo $cost_err; ?></span>
                  </div>


                <!-- /.card-body -->

                <div class="card-footer">
                  <?php if ($update) :?>
                  <button type="submit" class="btn btn-primary" name="categories_update">Update</button>
                  <?php else:?>
                  <button type="submit" class="btn btn-primary" name="categories_submit">Submit</button>
                <?php endif;?>
                </div>
              </div>
            </form>
          </div>
        </div>
            <!-- /.card -->
            <div class="col-md-6">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Registered Categories</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Shell Cost</th>
                      <th>Bottles Count</th>
                      <th>Bottles Cost</th>
                      <th colspan="2">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $number =1;
                    $res = $conn->query("SELECT * FROM product_categories") or die($conn->error);
                    while ($row= $res->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?php echo $number; ?></td>
                      <td><?php echo $row['name']; ?></td>
                      <td><?php echo $row['shell_cost']; ?></td>
                      <td><?php echo $row['bottles_number']; ?></td>
                      <td><?php echo $row['bottle_price']; ?></td>
                      <td style="width: 40px"><a href="categories.php?category_edit=<?php echo $row['id'];?>"><button class="btn btn-success">Edit</button></a></td>
                      <td style="width: 40px"><a href="categories.php?category_delete=<?php echo $row['id'];?>"><button class="btn btn-danger">Delete</button></a></td>

                    </tr>
                    <?php
                      $number++;
                    endwhile;
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
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

<?php
include __DIR__.'/../partials/scripts.php';
?>

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
