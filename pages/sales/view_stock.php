<?php
session_start();
require 'process.php';
if (!isset($_SESSION['group'])) {
  header('location: ../utils/logout.php');
}else{
  $username = $_SESSION['username'];
  $group = $_SESSION['group'];
}
function fill_users($conn)
{
  $sql = "SELECT id, name FROM users WHERE user_group=7";
  $output = '';
  $result = $conn->query($sql) or die($conn->error);
  while ($row=$result->fetch_assoc()) {
    $output .= "<option value=".$row['id'].">".$row['name']."</option>";
  }
  return $output;
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
        <div class="row">
          <div class="col-12">

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Current Stocks</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <select class="form-control" name="shop" id="shop">
                      <option value="0">Main Store</option>
                      <?php echo fill_users($conn); ?>
                    </select>
                  </div>

                </div>
                <table id="show_product" class="table table-bordered table-striped">
                  <?php
                  echo '<thead>
<tr>
  <th>Product Name</th>
  <th>Quantity<small>(In Stock)</small></th>
</tr>
</thead><tbody>';
                  $sql = "SELECT *,products.name as pname FROM stock INNER JOIN products ON stock.product=products.id";
                  $result = $conn->query($sql) or die($conn->error);
                  if (mysqli_num_rows($result)<1) {

                    $output .= "<tr><td colspan='2'>No data available</td></tr>";

                  } else {

                    while ($row = $result->fetch_assoc()) {

                      echo "<tr>";
                      echo "<td>".$row['pname']."</td>";
                      echo "<td>".$row['quantity_available']."</td>";
                      echo "</tr>";
                      
                    }

                  }
                  echo '</tbody>
  <tfoot>
  <tr>
    <th>Product Name</th>
    <th>Quantity<small>(In Stock)</small></th>
  </tr>
  </tfoot>';

                   ?>
                </table>
                <div class="col-sm-12">
            <!-- <button type="button" class="btn btn-primary pull-right" aria-label="Print" onclick="window.print()"><i class="fa fa-print"></i> Print</button> -->
            </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
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
<!-- page script -->

<!-- Datatable Buttons -->

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

<!-- Datatables Buttons End -->

<script>
  $(function () {
    $("#example1").DataTable();
    $('#show_product').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "dom": 'Bfrtip',
      "buttons": [
          'print', 'excel', 'pdf'
      ]
    });
  });
  $(document).ready(function(){
    $("#shop").change(function(){
      var salesman = $(this).val();
      $.ajax({
        url: "load_stock.php",
        method:"POST",
        data: {salesperson:salesman},
        success:function(data){
           $('#show_product').html(data);
         }
      })
    });
  });
</script>
  </body>
  </html>
