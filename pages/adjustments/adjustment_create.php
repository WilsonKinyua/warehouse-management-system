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
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <style>
    .box
    {
     width:1270px;
     background-color:#fff;
     border:1px solid #ccc;
     border-radius:5px;
     margin-top:25px;
     box-sizing:border-box;
    }
    </style>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="container box">
           <div class="table-responsive">
             <table class="table table-bordered table-striped" style="margin-top: 1em;">
               <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Expected Stock</th>
                    <th>Actual Quantity</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = "SELECT * FROM stock INNER JOIN products ON stock.product=products.id";
                  $res = $conn->query($query)or die($conn->error);
                  if (mysqli_num_rows($res)==0) {
                    echo "<td colspan='4'>No data found</td>";
                  }else{
                    while ($row = $res->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>".$row['name']."</td>";
                      echo "<td>".$row['category']."</td>";
                      echo "<td>".$row['quantity_available']."</td>";
                      echo "</tr>";
                    }
                  }
                   ?>
                </tbody>
               </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
              <!-- /.card-body -->
            <!-- /.card -->

        <!-- /.row -->

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
</body>
</html>
