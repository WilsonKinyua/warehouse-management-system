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
    <!--<div class="content-header">-->
    <!--  <div class="container-fluid">
    <!--    <div class="row mb-2">-->
    <!--      &nbsp;-->
    <!--    </div><!-- /.row --> 
    <!--  </div><!-- /.container-fluid --> 
    <!--</div>-->
    <!-- /.content-header -->
    <!-- Main content -->
   <section class="content">
     <div class="row">
       <div class="col-12">

         <div class="card card-primary">
           <div class="card-header">
             <h3 class="card-title">Register Customers</h3>
           </div>
           <!-- /.card-header -->
           <div class="card-body">
             <div class="row">
               <div class="col-md-11">&nbsp;</div>
               <div class="col-md-1">
                 <a href="customers_create.php" class="btn btn-primary">Add</a>
               </div>
             </div>
             <table id="example1" class="table table-bordered table-striped">
               <thead>
               <tr>
                 <th>Business Name</th>
                 <th>Contact</th>
                 <th>Credit Limit</th>
                 <th>Total Owed</th>
                 <th>Total Paid</th>
                 <th>Customer Group</th>
                 <th>Pay Terms</th>
               </tr>
               </thead>
               <tbody>
                 <?php
                 $res = $conn->query("SELECT * FROM customers ORDER BY cust_name") or die($conn->error);
                 while ($row = $res->fetch_assoc()) {
                   echo "<tr>";
                   echo "<td>".$row['cust_name']."</td>";
                   echo "<td>".$row['cust_contact']."</td>";
                   echo "<td>".$row['credit_limit']."</td>";
                   echo "<td>".$row['total_owed']."</td>";
                   echo "<td>".$row['total_paid']."</td>";
                     $grp_id = $row['cust_group'];
                     $grp = $conn->query("SELECT name FROM cust_groups WHERE id='$grp_id'") or die($conn->error);
                     $grp_row = $grp->fetch_array();
                     echo "<td>".$grp_row['name']."</td>";
                     echo "<td>".$row['pay_term']." Days </td>";
                   echo '<td style="display:none;"><a href="customers.php?supplier_delete='.$row['id'].'" class="btn btn-xs btn-danger"><i class="fas fa-trash-alt"></i> Delete</a></td>';
                   echo "</tr>";
                 }
                 ?>
               </tbody>
               <tfoot>
               <tr>
                 <th>Business Name</th>
                 <th>Contact</th>
                 <th>Credit Limit</th>
                 <th>Total Owed</th>
                 <th>Total Paid</th>
                 <th>Customer Group</th>
                 <th>Pay Terms</th>
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

 <?php
 include __DIR__.'/../partials/scripts.php';
 ?>
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
</script>
  </body>
  </html>
