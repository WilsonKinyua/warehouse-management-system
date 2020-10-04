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
                <h3 class="card-title">Detailed Purchase Report</h3>
              </div>
           
  <form method="POST"  class="form-inline">
  <table>
  <tr>
     <td>          
                <div class="col-md-4">
                <div class="form-group">
                <label for="expense_for"> Date From:</label>
                <input type="date" name="date_from" required value="<?php echo $result = $_POST['date_from'] ? $_POST['date_from'] : date(); ?>" class="form-control">
                    </div>
                  </div>
                    </td>
                    <td>
                  <div class="col-md-4">
                  <div class="form-group">
                  <label for="expense_for">Date To:</label>
                  <input type="date" name="date_to" required value="<?php echo $result = $_POST['date_to'] ? $_POST['date_to'] :  date(); ?>" class="form-control">
                    </div>
                  </div>
                </div>
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
                            $user = $conn->query("SELECT name, code FROM stores where code IN('$store_code')") or die($conn->error); 
                            $column = $user->fetch_array();
                            echo "<option selected value=".$_POST['store_code'].">".$column['name']."</option>";
                            echo "<option value='all'>All Stores</option>";

                          }
                        ?> 
                   
                        <?php

                          $stores = $conn->query("SELECT id, name, code FROM stores order by name") or die($conn->error);

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

                      <th>Product</th>
                      <th>Total Purchases</th>
                      <th>Total Sold</th>
                      <th>Current Stock</th>
                      
                  </tr>
                  </thead>
                  <tbody>
                     
                  <?php
                    
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        
                      if($_POST['store_code'] == 'all') {

                        echo "Here";
                          
                        $from = $_POST['date_from'] ;
                        $to = $_POST['date_to'] ;
                        
                            // Total Calculation 
                        // $qry = $conn->query("SELECT FORMAT(SUM(total_amount),2) AS TOTAL FROM stock_detail WHERE date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to' AS DATE), INTERVAL 1 DAY) AND stock_detail.trn_type = '1' ") or die($conn->error);
                        // $tot = $qry->fetch_array();
                          
                        $res = $conn->query(
                          
                            "SELECT products.name AS product_name, products.id,
                              (
                                SELECT SUM(stock_detail.trn_qty) FROM stock_detail WHERE stock_detail.product=products.id
                              ) total_purchases,
                              (
                                SELECT SUM(stock_mobile.sold) FROM stock_mobile WHERE stock_mobile.product=products.id
                              ) total_sold,
                              (
                                SELECT stock.quantity_available FROM stock WHERE stock.product=products.id
                              ) current_stock_quantity,
                              (
                                  
                                (SELECT stock.quantity_available FROM stock WHERE stock.product=products.id) 
                                  - 
                                  (SELECT SUM(stock_detail.trn_qty) FROM stock_detail WHERE stock_detail.product=products.id)
                                  + 
                                  (SELECT SUM(stock_mobile.sold) FROM stock_mobile WHERE stock_mobile.product=products.id)
                                  
                              ) item_opening_balance
                              
                            FROM products
                            "
                        ) or die($conn->error);

                        while ($row = $res->fetch_assoc()) {

                          echo "<tr>";

                              echo "<td>".$row['product_name']."</td>";
                              echo "<td>".$row['total_purchases']."</td>";
                              echo "<td>".$row['total_sold']."</td>";
                              echo "<td>".$row['current_stock_quantity']."</td>";
                              echo "<td>".$row['item_opening_balance']."</td>";
                              
                          echo "</tr>";
                          
                        } 

                    } else if($_POST['store_code'] !== 'all') {

                        // collect value of input field
                        $store_code = $_POST['store_code'];
                        $from = $_POST['date_from'];
                        $to = $_POST['date_to'];
                        
                        //   Total Calculation   
                        $qry = $conn->query(

                            "SELECT FORMAT(SUM(total_amount),2) AS TOTAL FROM stock_detail 
                            LEFT JOIN products ON products.id=stock_detail.product
                            WHERE products.vendor='$store_code' AND date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to' AS DATE), INTERVAL 1 DAY) AND stock_detail.trn_type = '1'"

                        ) or die($conn->error);

                        $tot = $qry->fetch_array();
                          
                        $res = $conn->query(

                            "SELECT stock_detail.date, stock_detail.doc_number, supplier.name AS supplier, stores.name AS nstore, products.name AS product, stock_detail.trn_qty AS qty,stock_detail.total_amount AS amount FROM `stock_detail` 
                            LEFT JOIN products ON stock_detail.product=products.id
                            LEFT JOIN supplier ON stock_detail.entity=supplier.id
                            LEFT JOIN stores ON stock_detail.store=stores.id 
                            WHERE products.vendor IN('$store_code') AND stock_detail.date BETWEEN CAST('$from' AS DATE) AND DATE_ADD(CAST('$to' AS DATE), INTERVAL 1 DAY) AND stock_detail.trn_type = '1' 
                            ORDER BY stock_detail.date,products.name"

                        ) or die($conn->error);

                        while ($row = $res->fetch_assoc()) {

                          echo "<tr>";

                            echo "<td>".$row['date']."</td>";
                            echo "<td>".$row['doc_number']."</td>";
                            echo "<td>".$row['supplier']."</td>";
                            echo "<td>".$row['nstore']."</td>";
                           
                          echo "</tr>";  
                                            
                        } 

                   }
            }  
                    
                  "</tbody>";
                  "<tfoot>";
                       
                   echo  "<tr>";

                     echo  "<th></th>";
                     echo  "<th></th>";
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

<!-- footer -->
<?php include __DIR__.'/../partials/scripts.php'; ?>
<!-- /.footer -->


 <!--DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>

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

 <!--Page specific script -->
<script type="text/javascript">
$(function () {

  let table = $('#Sales_table').DataTable({
    "paging": false,
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

  table.buttons().container()
  .appendTo( '#example_wrapper .col-md-6:eq(0)' );

});
</script>
 
  </body>
  </html>
