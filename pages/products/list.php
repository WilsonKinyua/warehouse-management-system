<?php include 'process.php';
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
     <div class="row">
       <div class="col-12">

         <div class="card card-primary">
           <div class="card-header">
             <h3 class="card-title">Product List</h3>
           </div>
           <!-- /.card-header -->
           <div class="card-body">
             <div class="row">
               <div class="col-md-11">&nbsp;</div>
               <div class="col-md-1">
                 <a href="products_create.php" class="btn btn-primary" style="display:none;">Add</a>
               </div>
             </div>
             <table id="table-here" class="table table-bordered table-striped">
               <thead>
               <tr>
                  <th style="width: 10px">#</th>
                  <th>Product Name</th>
                  <th>Category</th>
                  <th>Cost</th>
                  <th>Stockist Price</th>
                  <th>Retail Price</th>
                  <th>Special Cat 1</th>
                  <th>Special Cat 2</th>
                  <th>Special Cat 3</th>
               </tr>
               </thead>
               <tbody>
               <?php
                  $number =1;
                  // $conn = mysqli_connect('localhost','root','','premierdb') or die($conn->error);
                  $res = $conn->query("SELECT * FROM products ORDER BY name") or die($conn->error);
                  while ($row= $res->fetch_assoc()):
                  ?>
               <tr>
                 <td><?php echo $number; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['price']; ?></td>
                <td><?php echo $row['stockist']; ?></td>
                <td><?php echo $row['retail']; ?></td>
                <td><?php echo $row['distributor']; ?></td>
                <td><?php echo $row['selling_cost']; ?></td>
                <td><?php echo $row['wholesale']; ?></td>
                 
               </tr>
               <?php $number++;endwhile;  ?>
              
               </tbody>
               <tfoot>
               <tr>
                 <th style="width: 10px"></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th></th>
                  <th>
                  </th>
               </tr>
               </tfoot>
             </table>
        
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

    <?php include __DIR__.'/../partials/footer.php'; ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    
  </div>
  <!-- ./wrapper -->

<?php include __DIR__.'/../partials/scripts.php'; ?>
<!-- page script -->

<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

<!-- Datatable Buttons -->

<!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>

<!-- Datatables Buttons End -->

<script type="text/javascript">
  $(function () {
    
    $("#table-here").DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": true,
      "autoWidth": true,
      "dom": 'Bfrtip',
      "buttons": [
          'print', 'excel', 'pdf'
      ]
    });
    
  });
</script>
  </body>
  </html>
