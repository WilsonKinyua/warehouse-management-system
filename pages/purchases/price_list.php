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
           <br />
            <div align="right">
             <button type="button" name="add" id="add" class="btn btn-info">Add</button>
            </div>
            <br />
            <div id="alert_message"></div>
                <table id="user_data" class="table table-bordered table-striped" style="width: 125%; margin-top: 2em;">
                 <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Cost</th>
                    <th>RRP</th>
                    <th>Distributor</th>
                    <th>Stockist</th>
                     <th>Wholesale</th>
                     <th>Retail</th>
                     <th>Action</th>
                  </tr>
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

  $('#add').click(function(){
   var html = '<tr>';
   html += '<td contenteditable id="data1"></td>';
   html += '<td contenteditable id="data2"></td>';
   html += '<td contenteditable id="data3"></td>';
   html += '<td contenteditable id="data4"></td>';
   html += '<td contenteditable id="data5"></td>';
   html += '<td contenteditable id="data6"></td>';
   html += '<td contenteditable id="data7"></td>';
   html += '<td contenteditable id="data8"></td>';
   html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs">Insert</button></td>';
   html += '</tr>';
   $('#user_data tbody').prepend(html);
  });
// 'name','category','price','selling_cost','distributor','stockist','wholesale','retail'
  $(document).on('click', '#insert', function(){
   var name = $('#data1').text();
   var category = $('#data2').text();
   var price = $('#data3').text();
   var selling_cost = $('#data4').text();
   var distributor = $('#data5').text();
   var stockist = $('#data6').text();
   var wholesale = $('#data7').text();
   var retail = $('#data8').text();
   if(name != '' && category != '' && price != '' && selling_cost != '')
   {
    $.ajax({
     url:"product_list/insert.php",
     method:"POST",
     data:{name:name, category:category, price:price,distributor:distributor, selling_cost:selling_cost,stockist:stockist, wholesale:wholesale,retail:retail},
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
   else
   {
    alert("Both Fields is required");
   }
  });

  $(document).on('click', '.delete', function(){
   var id = $(this).attr("id");
   if(confirm("Are you sure you want to remove this?"))
   {
    $.ajax({
     url:"product_list/delete.php",
     method:"POST",
     data:{id:id},
     success:function(data){
      $('#alert_message').html('<div class="alert alert-success">'+data+'</div>');
      $('#user_data').DataTable().destroy();
      fetch_data();
     }
    });
    setInterval(function(){
     $('#alert_message').html('');
    }, 5000);
   }
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
