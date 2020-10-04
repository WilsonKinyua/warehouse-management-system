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
            <div id="alert_message"></div>
            <div class="col-md-12">
              <table id="user_data" class="table table-bordered table-striped col-md-12" style="">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Quantity Available</th>
                  </tr>
                </thead>
              </table>
            </div>
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
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<script type="text/javascript" language="javascript" >
 $(document).ready(function(){

  fetch_data();

  function fetch_data()
  {
   var dataTable = $('#user_data').DataTable({
    "processing" : true,
    "serverSide" : true,
    "order" : [],
    "ajax" : {
     url:"product_list/fetch.php",
     type:"POST"
    }
   });
  }

  function update_data(id, column_name, value)
  {
   $.ajax({
    url:"product_list/update.php",
    method:"POST",
    data:{id:id, column_name:column_name, value:value},
    success:function(data)
    {
     $('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
     $('#user_data').DataTable().destroy();
     fetch_data();
    }
   });
   setInterval(function(){
    $('#alert_message').html('');
   }, 5000);
  }

  $(document).on('blur', '.update', function(){
   var id = $(this).data("id");
   var column_name = $(this).data("column");
   var value = $(this).text();
   update_data(id, column_name, value);
  });

 });
</script>

<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/fullcalendar/main.min.js"></script>
<script src="../../plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="../../plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="../../plugins/fullcalendar-interaction/main.min.js"></script>
<script src="../../plugins/fullcalendar-bootstrap/main.min.js"></script>
<!-- Page specific script -->
<script>

</script>
</body>
</html>
