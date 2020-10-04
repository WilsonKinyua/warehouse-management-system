<?php include '../utils/conn.php';
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
<?php 
    include __DIR__.'/../partials/head.php'; 

    // $stores = $conn->query("SELECT id, name, code FROM stores order by name") or die($conn->error);
    //   while ($row=$stores->fetch_assoc()) {
    //     echo json_encode($row);
    //   }

    // $store_code = $_POST['store_code'];
    // $user = $conn->query("SELECT name, id, code FROM stores where code='$store_code'") or die($conn->error); 
    // $column = $user->fetch_array();
    // echo json_encode($column);
?>
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

    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Sales Summary Report </h3>
              </div>
           
  <form method="POST"  class="form-inline">
  <table>
  <tr>
     <td>          
                <div class="col-md-4">
                <div class="form-group">
                <label for="expense_for">Date From:</label>
                <input type="date" name="date_from" required value="<?php echo $from = $_POST['date_from'] ? $_POST['date_from'] : date(); ?>" class="form-control">
                    </div>
                  </div>
                    </td>
                    <td>
                  <div class="col-md-4">
                  <div class="form-group">
                  <label for="expense_for">Date To:</label>
                  <input type="date" name="date_to" required value="<?php echo $to = $_POST['date_to'] ? $_POST['date_to'] : date(); ?>" class="form-control">
                    </div>
                  </div>
                </div>
                </td>
                     <td> <label>Select Salesman:</label>
  <!--<td>  <select class="form-control" id="id" name="id" required>-->
		          <select class="form-group" id="name" name="id" required>

                <?php 

                    if(!isset($_POST['id']) or $_POST['id'] == "all") {

                      echo "<option value='all'>All Salesmen</option>";

                    } else {

                      $id = $_POST['id'];
                      $user = $conn->query("SELECT name FROM users where id='$id'") or die($conn->error); 
                      $column = $user->fetch_array();
                      echo "<option selected value=".$_POST['id'].">".$column['name']."</option>";
                      echo "<option value='all'>All Salesmen</option>";

                  }
                      
                ?>

                <?php
                $stores = $conn->query("SELECT id, name FROM users order by name") or die($conn->error);
                while ($row=$stores->fetch_assoc()) {
                  echo "<option value=".$row['id'].">".$row['name']."</option>";}
                    ?>
                  
            </select>
            
            </td>

            <!--***********************Store****************************** -->
            <td> 
                <label>Select Store:</label>
		            <select class="form-group" id="id" name="store_code" required>

                      <?php 

                          if(!isset($_POST['store_code']) or $_POST['store_code'] == "all") {

                            echo "<option value='all'>All Stores</option>"; 

                          } else {

                            $store_code = $_POST['store_code'];
                            $user = $conn->query("SELECT name, id, code FROM stores where code='$store_code'") or die($conn->error); 
                            $column = $user->fetch_array();
                            echo "<option selected value=".$_POST['store_code'].">".$column['name']."</option>";
                            echo "<option value='all'>All Stores</option>"; 

                          }
                        ?> 
                  
                        <?php

                          $stores = $conn->query("SELECT id, code, name FROM stores order by name") or die($conn->error);
                          while ($row=$stores->fetch_assoc()) {
                            echo "<option value=".$row['code'].">".$row['name']."</option>";
                            
                          }

                        ?>
                </select>
                    
            </td>


 <td>
 <input type="submit" class="btn bg-info btn-block" id="submit_search" onclick="loader()" name="search" value=" Search">
 </td>
 <td><div id="load" class="loader"></div></td>
 
</tr>
</table>
</form>	
                <hr>
                <div  style="overflow-x:auto;">
                <table id="Sales_table" class="table table-bordered table-striped"  style="overflow-x:auto;">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Salesman</th>
                      <th>Total Amount</th>
                  </tr>
                  </thead>
                  <tbody>
                     
                    <?php
                    
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        
                  if($_POST['id'] == 'all' and $_POST["store_code"] == "all") {
                            
                        $from = $_POST['date_from'] ;
                        $to = $_POST['date_to'] ;
                        
                        // Total Calculation 
                        $qry = $conn->query("SELECT FORMAT(SUM(`quantity`* `sale_price`), 2) AS TOTAL FROM new_sale WHERE `salesman` IN (Select id FROM users) AND date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to' AS DATE), INTERVAL 1 DAY)") or die($conn->error);
                        $tot = $qry->fetch_array();
                          
                        $res = $conn->query(

                          "SELECT CAST(new_sale.date AS DATE) AS date, users.name, FORMAT(sum(`sale_price` * `quantity`), 2) As amount FROM new_sale 
                          LEFT JOIN users on new_sale.salesman=users.id
                          WHERE new_sale.date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to ' AS DATE), INTERVAL 1 DAY)
                          Group By  users.name, CAST(new_sale.date AS DATE) ORDER BY CAST(new_sale.date AS DATE), users.name"

                        ) or die($conn->error);

                        while ($row = $res->fetch_assoc()) {

                          echo "<tr>";
                          // echo json_encode($row);
                          // echo "<br>";
                          echo "<td>".$row['date']."</td>";
                          echo "<td>".$row['name']."</td>";
                          echo "<td>".$row['amount']."</td>";
                          echo "</tr>";
                          
                        } 
                   } else if($_POST['id'] !== 'all' and $_POST["store_code"] == "all") {

                        // collect value of input field
                        $id = $_POST['id'];
                        $from = $_POST['date_from'];
                        $to = $_POST['date_to'] ;
                        $store_code = $_POST["store_code"];
                        
                        //   Total Calculation   
                        $qry = $conn->query("SELECT FORMAT(SUM(`quantity`* `sale_price`),2) AS TOTAL FROM new_sale WHERE `salesman` IN ('$id') AND date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to' AS DATE), INTERVAL 1 DAY)") or die($conn->error);
                        $tot = $qry->fetch_array();
                          
                        $res = $conn->query(

                            "SELECT CAST(new_sale.date AS DATE) AS date, users.name, FORMAT(sum(`sale_price` * `quantity`),2) As amount FROM new_sale 
                            LEFT JOIN users on new_sale.salesman=users.id
                            WHERE new_sale.salesman IN('$id') AND new_sale.date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to ' AS DATE), INTERVAL 1 DAY)
                            Group By  users.name, CAST(new_sale.date AS DATE) ORDER BY CAST(new_sale.date AS DATE), users.name"

                        ) or die($conn->error);

                        while ($row = $res->fetch_assoc()) {

                          echo "<tr>";

                          // echo json_encode($row);
                          // echo "<br>";

                          echo "<td>".$row['date']."</td>";
                          echo "<td>".$row['name']."</td>";
                          echo "<td>".$row['amount']."</td>";

                          echo "</tr>"; 

                        } 
                       
                   } else if($_POST['id'] !== 'all' and $_POST["store_code"] !== "all") {

                    // collect value of input field
                    $id = $_POST['id'];
                    $from = $_POST['date_from'];
                    $to = $_POST['date_to'];
                    $store_code = $_POST["store_code"];

                    //   Total Calculation   
                    $qry = $conn->query(
                      "SELECT FORMAT(SUM(`quantity`* `sale_price`),2) AS TOTAL FROM new_sale 
                      INNER JOIN products on products.id=new_sale.product
                      WHERE `salesman` IN ('$id') AND products.vendor='$store_code' AND date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to' AS DATE), INTERVAL 1 DAY)"
                    ) or die($conn->error);
                    $tot = $qry->fetch_array();
                      
                    $res = $conn->query(

                        "SELECT CAST(new_sale.date AS DATE) AS date, users.name, FORMAT(sum(`sale_price` * `quantity`),2) As amount, products.vendor FROM new_sale 
                        INNER JOIN users on new_sale.salesman=users.id
                        INNER JOIN products on products.id=new_sale.product
                        WHERE new_sale.salesman IN('$id') AND products.vendor='$store_code' AND new_sale.date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to ' AS DATE), INTERVAL 1 DAY)
                        Group By products.vendor, users.name, CAST(new_sale.date AS DATE) ORDER BY CAST(new_sale.date AS DATE), users.name"

                    ) or die($conn->error);

                    while ($row = $res->fetch_assoc()) {

                      echo "<tr>";

                      // echo json_encode($row);
                      // echo "<br>";

                      echo "<td>".$row['date']."</td>";
                      echo "<td>".$row['name']."</td>";
                      echo "<td>".$row['amount']."</td>";

                      echo "</tr>"; 

                    } 
                   
               } else if($_POST['id'] == 'all' and $_POST["store_code"] !== "all") {

                    // collect value of input field
                    $id = $_POST['id'];
                    $from = $_POST['date_from'];
                    $to = $_POST['date_to'];
                    $store_code = $_POST["store_code"];
                    
                    //   Total Calculation   
                    $qry = $conn->query(
                      "SELECT FORMAT(SUM(`quantity`* `sale_price`),2) AS TOTAL FROM new_sale 
                      INNER JOIN products on products.id=new_sale.product
                      WHERE products.vendor='$store_code' AND date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to' AS DATE), INTERVAL 1 DAY)"
                    ) or die($conn->error);
                    $tot = $qry->fetch_array();
                      
                    $res = $conn->query(

                        "SELECT CAST(new_sale.date AS DATE) AS date, users.name, FORMAT(sum(`sale_price` * `quantity`),2) As amount, products.vendor FROM new_sale 
                        INNER JOIN users on new_sale.salesman=users.id
                        INNER JOIN products on products.id=new_sale.product
                        WHERE products.vendor='$store_code' AND new_sale.date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to ' AS DATE), INTERVAL 1 DAY)
                        Group By products.vendor, users.name, CAST(new_sale.date AS DATE) ORDER BY CAST(new_sale.date AS DATE), users.name"

                    ) or die($conn->error);

                    while ($row = $res->fetch_assoc()) {

                      echo "<tr>";

                      // echo json_encode($row);
                      // echo "<br>";

                      echo "<td>".$row['date']."</td>";
                      echo "<td>".$row['name']."</td>";
                      echo "<td>".$row['amount']."</td>";

                      echo "</tr>"; 

                    } 
                  
              }
            }  
                    
                  "</tbody>";
                  "<tfoot>";
                       
                   echo  "<tr>";
                    echo  "<th></th>";
                    echo  "<th>Total</th>";
                    echo  $total = !isset($tot) ? "<th> 0 </th>" : "<th>".$tot['TOTAL']."</th>";

                   echo  "</tr>";

                  echo  " </tfoot>";
                  
                  ?>
                </table>
               
            <!-- <div class="col-sm-12">
            <button type="button" class="btn btn-primary pull-right" aria-label="Print" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
            </div> -->
                </div>
              </div>
              <!-- /.card-body -->
          </div>
        </div>
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
   <!--./wrapper -->

<!--<p>Click the button to print the current page.</p>-->

<!-- scripts -->
<?php include __DIR__.'/../partials/scripts.php'; ?>
<!-- /.scripts -->

 <!--DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
 <!--Page specific script -->


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
  $('#Sales_table').DataTable({
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
