<?php require 'process.php'; ?>
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
    <!-- Content Header (Page header) 
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          &nbsp;
        </div>
      </div>
    </div> -->
    <!-- /.content-header -->
    <!-- Main content -->
   <section class="content">
     <div class="row">
       <div class="col-12">

         <div class="card card-primary">
           <div class="card-header">
             <h3 class="card-title">Register Suppliers</h3>
           </div>
           <!-- /.card-header -->
           <div class="card-body">
             <div class="row">
               <div class="col-md-11">&nbsp;</div>
               <div class="col-md-1">
                 <a href="supplier_create.php" class="btn btn-primary">Add</a>
               </div>
             </div>
             <table id="example1" class="table table-bordered table-striped">
               <thead>
               <tr>
                 <th>Business Name</th>
                 <th>Contact</th>
                 <th>Balance</th>
                 <th>KRA PIN</th>
                 <th>Pay Term</th>
                 <th>Action</th>
               </tr>
               </thead>
               <tbody>
                 <?php
                 $res = $conn->query("SELECT * FROM supplier") or die($conn->error);
                 while ($row = $res->fetch_assoc()) {
                   echo "<tr>";
                   echo "<td>".$row['name']."</td>";
                   echo "<td>".$row['contact']."</td>";
                   echo "<td>".$row['balance']."</td>";
                   echo "<td>".$row['kra_pin']."</td>";
                   if ($row['pay_term'] == 30) {
                     echo "<td>Monthly</td>";
                   }elseif ($row['pay_term'] == 90) {
                     echo "<td>Quarterly</td>";
                   }elseif ($row['pay_term'] == 7) {
                     echo "<td>Weekly</td>";
                   }else{
                     echo "<td>Undefined</td>";
                   }
                   echo '<td><a href="suppliers.php?supplier_delete='.$row['id'].'" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i> Delete</a></td>';
                   echo "</tr>";
                 }
                  ?>

               </tbody>
               <tfoot>
               <tr>
                 <th>Business Name</th>
                 <th>Contact</th>
                 <th>Balance</th>
                 <th>KRA PIN</th>
                 <th>Pay Term</th>
                 <th>Action</th>
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
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- page script -->
<script>
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
      console.log(current)
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
