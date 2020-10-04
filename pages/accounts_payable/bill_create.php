<?php include "../utils/conn.php";
session_start();
ob_start();
// include "bill_print.php";
$message ="";
if (!isset($_SESSION['group'])) {
    header('location: ../utils/logout.php');
} else {
    $username = $_SESSION['username'];
    $group = $_SESSION['group'];
}

if(isset($_POST['add_supply'])){
    if(isset($_SESSION["supply_cart"])) {
        $item_array_id = array_column($_SESSION["supply_cart"], "item_id");
        if (!in_array($_GET["id"], $item_array_id)) {
            $count = count($_SESSION["supply_cart"]);
            $item_array = array(
                    // 'item_id' => $_POST['id'],
                    'item_id'   => $_GET['id'],
                    'item_name' => $_POST["name"],
                    'qty'       => $_POST["qty"],
                    'price'     => $_POST["price"],
                    'acc'       => $_POST["acc"],
                );
            $_SESSION["supply_cart"][$count] = $item_array;
        } else {
            echo '<script>alert("Item Already Added")</script>';  
            echo '<script>window.location="bill_create.php"</script>'; 
        }
    } else {
        $item_array = array(
            'item_id'   => $_GET['id'],
            'item_name' => $_POST["name"],
            'qty'       => $_POST["qty"],
            'price'     => $_POST["price"],
            'acc'       => $_POST["acc"],
        );
         $_SESSION["supply_cart"][0] = $item_array;
        }
    }
    
    
    if(isset($_GET["action"]))  
    {  
         if($_GET["action"] == "delete")  
         {  
              foreach($_SESSION["supply_cart"] as $keys => $values)  
              {  
                   if($values["item_id"] == $_GET["id"])  
                   {  
                        unset($_SESSION["supply_cart"][$keys]);  
                        echo '<script>window.location="bill_create.php"</script>';  
                   }  
              }  
         }elseif($_GET["action"] == "add" && isset($_GET['qty'])) {

         }
    }  
    
    
    if(isset($_POST['clear_supply_cart'])){
        unset($_SESSION['supply_cart']);
        header('location: bill_create.php');
    }

     if (isset($_POST['update_supply_cart'])) {
         foreach ($_SESSION['supply_cart'] as $index => $item) {
             $newQtys = array_values($_POST['qty']);
             $val = $newQtys[$index];
             if ($val == 0) {
                 unset($_SESSION['supply_cart'][$index]);
             } else {
                 $_SESSION['supply_cart'][$index]['qty'] = $val;
             }
         }
         header('location: bill_create.php');
     }


     if(isset($_POST['save_supply'])) {

         $date               =  date('Y-m-d H:i:s');
         $supply_date        =  $date;
         $supply_due_date    =  $_POST['supply_due_date'];
         $description        =  $_POST['description'];
         $doc_number         =  $_POST['doc_number'];
         $total_amount       =  $_POST['total_amount'];
         $supplier_id        =  $_POST['supplier_name'];
         $acc                =  $_POST['acc'];

        if(empty($supplier_id)) {
            $_SESSION['supplier_input'] = $supplier_id;
            $_SESSION['error_spplier'] = 'Suppler must be selected';
            header("Location: bill_create.php");
        }

        if(empty($total_amount )) {

            header("Location: bill_create.php");
        }

        if(empty($acc)) {

            $conn->query("INSERT INTO `bills`(`tran_date`, `due_date`, `description`, `reference`, `total`, `status`, `supplier_id`, `bill_payment_id`, `coa_id`) 
            VALUES ('$supply_date','$supply_due_date','$description','$doc_number','$total_amount',0,'$supplier_id',NULL,310)") or die($conn->error);
             $bill_id = $conn->insert_id;

            foreach ($_SESSION['supply_cart'] as $key => $values) {
                $name = $values['item_name'];
                $qty = $values['qty'];
                $price = $values['price'];
                $line_amount = $values['qty'] * $values['price'];
                $bill_lines = $conn->query("INSERT INTO `bill_lines`(`name`, `quantity`, `line_amount`, `bill_id`, `line_coa_id`) 
                 VALUES ('$name','$qty','$line_amount',$bill_id,'$acc')") or die($conn->error);

            }
         
               unset($_SESSION['supply_cart']);
               setcookie("invoice_success", true, time()+10);
               header("Location: bill_create.php");

        } else {

         $results = $conn->query("INSERT INTO `bills`(`tran_date`, `due_date`, `description`, `reference`, `total`, `status`, `supplier_id`, `bill_payment_id`, `coa_id`) 
         VALUES ('$supply_date','$supply_due_date','$description','$doc_number','$total_amount',0,'$supplier_id',NULL,'$acc')") or die($conn->error);

        $bill_id = $conn->insert_id;

        foreach ($_SESSION['supply_cart'] as $key => $values) {

            $name = $values['item_name'];
            $qty = $values['qty'];
            $price = $values['price'];
            $line_amount = $values['qty'] * $values['price'];
            
            $bill_lines = $conn->query("INSERT INTO `bill_lines`(`name`, `quantity`, `line_amount`, `bill_id`, `line_coa_id`) 
            VALUES ('$name','$qty','$line_amount',$bill_id,'$acc')") or die($conn->error);
                    
                }
                unset($_SESSION['supply_cart']);
                setcookie("invoice_success", true, time()+10);
                header("Location: bill_create.php");
        exit();
    }
}
    //  }
    if(isset($_POST['save_print'])) {

        $date               =  date('Y-m-d H:i:s');
        $supply_date        =  $date;
        $supply_due_date    =  $_POST['supply_due_date'];
        $description        =  $_POST['description'];
        $doc_number         =  $_POST['doc_number'];
        $total_amount       =  $_POST['total_amount'];
        $supplier_id        =  $_POST['supplier_name'];
        $acc                =  $_POST['acc'];

       if(empty($supplier_id)) {

           $_SESSION['error_spplier'] = 'Suppler must be selected';
           header("Location: bill_create.php");
       }

       if(empty($total_amount )) {

           header("Location: bill_create.php");
       }

       if(empty($acc)) {

           $conn->query("INSERT INTO `bills`(`tran_date`, `due_date`, `description`, `reference`, `total`, `status`, `supplier_id`, `bill_payment_id`, `coa_id`) 
           VALUES ('$supply_date','$supply_due_date','$description','$doc_number','$total_amount',0,'$supplier_id',NULL,310)") or die($conn->error);
            $bill_id = $conn->insert_id;

           foreach ($_SESSION['supply_cart'] as $key => $values) {
               $name = $values['item_name'];
               $qty = $values['qty'];
               $price = $values['price'];
               $line_amount = $values['qty'] * $values['price'];
               $bill_lines = $conn->query("INSERT INTO `bill_lines`(`name`, `quantity`, `line_amount`, `bill_id`, `line_coa_id`) 
                VALUES ('$name','$qty','$line_amount',$bill_id,'$acc')") or die($conn->error);

           }
        
              unset($_SESSION['supply_cart']);
              setcookie("invoice_success", true, time()+10);
              $_SESSION['dont_close_alert'] = $bill_id;
              header("Location: bill_create.php");
           exit();

       } else {

        $results = $conn->query("INSERT INTO `bills`(`tran_date`, `due_date`, `description`, `reference`, `total`, `status`, `supplier_id`, `bill_payment_id`, `coa_id`) 
        VALUES ('$supply_date','$supply_due_date','$description','$doc_number','$total_amount',0,'$supplier_id',NULL,'$acc')") or die($conn->error);

       $bill_id = $conn->insert_id;

       foreach ($_SESSION['supply_cart'] as $key => $values) {

           $name = $values['item_name'];
           $qty = $values['qty'];
           $price = $values['price'];
           $line_amount = $values['qty'] * $values['price'];
           
           $bill_lines = $conn->query("INSERT INTO `bill_lines`(`name`, `quantity`, `line_amount`, `bill_id`, `line_coa_id`) 
           VALUES ('$name','$qty','$line_amount',$bill_id,'$acc')") or die($conn->error);
                   
               }
               unset($_SESSION['supply_cart']);
               setcookie("invoice_success", true, time()+10);
               $_SESSION['dont_close_alert'] = $bill_id;
               header("Location: bill_create.php");
       exit();
   }
}

?>
<!DOCTYPE html>
<html>
<?php
include __DIR__ . '/../partials/head.php';
?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php include __DIR__ . '/../partials/navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        <!--    end sidebar-->


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->

            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">

                        <?php if(isset($_COOKIE['invoice_success']) and $_COOKIE['invoice_success']) {?>
                            <div class="container-fluid">

                                <div class="row">
                                    <div class="col-md-12 pt-4 text-center">                    
                                    <div id="notif" class="alert alert-success text-center alert-dismissible fade show mt-4" role="alert">
                                    <strong>Invoice Created Successfully</strong>
                                    <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                    
                                </div>
                                </div>

                            </div>

                        <?php } ?>

                        <script>
                            setTimeout(function() {
                                let alerter = document.getElementById('notif')
                                if(alerter) {
                                alerter.parentNode.removeChild(alerter);
                                }
                            }, 30000)

                        </script>
                        </div>
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Suppy Sale</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form role="form" action="bill_create.php" method="post">
                                    <!-- <input type="hidden" name="salesman" value=""> -->
                                    <div class="card-body">
                                        <div class="form-group">
                                            <div class="row">
        
                                                <div class="col-md-4">
                                                    <label>Receipt Number: </label>
                                                    <input type="text" name="doc_number" value="INP-<?php echo rand(1000, 9999) . rand(1000, 9999); ?>" class="form-control" readonly>
                                                    <span style="color: red;"></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="supplier_name">Supplier: <br> <span style="color:red;">
                                                    <?php
                                                    if(isset($_SESSION['error_spplier'])) {
                                                            echo $_SESSION['error_spplier'];
                                                    }
                                                    unset($_SESSION['error_spplier']);
                                                    ?>
                                                </span></label>
                                                    <select class="form-control" name="supplier_name" id="supplier" onchange="pickCost()">
                                                        
                                                    <?php
                                                    if(isset($_POST['supplier_name'])){

                                                        $supplier_name = $_POST['supplier_name'];
                                                        $supplier = $conn->query("SELECT id,name FROM supplier WHERE id = '$supplier_name'") or die($conn->error);
                                                        $result = $supplier->fetch_array();
                                                        echo "<option selected value=" . $row['id'] . "> " . $row['name'] . "</option>";

                                                        } else {

                                                        echo "<option value='' disabled selected>--Select Supplier--</option>";
                                                        $query = $conn->query("SELECT id,name FROM supplier");
                                                        while ($row = mysqli_fetch_assoc($query)) {
                                                            echo  "<option value=" . $row['id'] . "> " . $row['name'] . "</option>";
                                                    }
                                                    }
                                                    ?>
                                                     </select>
                                                    <span style="color:red;"></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Supply Date: </label>
                                                    <input type="date" name="supply_date" value="<?php echo date('Y-m-d'); ?>" class="form-control" readonly>
                                                </div>

                                                <div class="row col-md-12">
                                                <div class="col-md-4">
                                                    <label>Due Date: </label>
                                                    <input type="date" name="supply_due_date" value="<?php echo (isset($_POST['supply_due_date'])) ?  $_POST['supply_due_date'] : date('Y-m-d') ?>" class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="acc">Account: </label>
                                                    <select class="form-control" name="acc" id="acc" onchange="pickCost()">
                                                    <!-- <option value="" disabled selected>--Select Account--</option> -->
                                                        <?php
                                                        $query = $conn->query("SELECT id,name FROM coa ORDER BY name ASC");
                                                        while ($row = mysqli_fetch_assoc($query)) {

                                                            if($row['id'] === '310') {
                                                                echo  "<option value=" . $row['id'] . " selected> " . $row['name'] . "</option>";
                                                            } else {
                                                                echo  "<option value=" . $row['id'] . " > " . $row['name'] . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                <?php echo (isset($_POST['select']) && $_POST['select'] === 'option1') ? 'selected' : ''; ?>
                                                    </select>
                                                    <span style="color:red;"></span>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Description: </label>
                                                    <textarea name="description" placeholder="Invoice description" class="form-control"><?php echo isset($_POST['description']) ? $_POST['description'] : '' ?></textarea>
                                                </div>
                                            </div>
                                                <div class="col-md-12">
                                                    
                                                </div>
                                                <div class="col-md-2">
                                                    &nbsp;
                                                </div>
                                                <div class="col-md-8">

                                                    </div>
                                                <div class="col-md-2">
                                                    &nbsp;
                                                </div>
                                                <script src="js/jquery.min.js"></script>
                                                <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                                                <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                                                <script>
                                                  
                                                  
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-4">
                                        <div class="col-md-11">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal" data-whatever="@add">Add</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <form method="post" action="bill_create.php?page=supply_cart">
                                            <table class="table table-bordered table-striped col-md-11" style="width: 100%; margin-left: 2em;">
                                                <tr>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Subtotal</th>
                                                <th>Accounts</th>
                                                <th>Remove</th>
                                                </tr>
                                                    <?php
                                                    if (!empty($_SESSION["supply_cart"])) {                                                        $total = 0;
                                                        foreach ($_SESSION["supply_cart"] as $keys => $values) {
                                                            
                                                    ?>
                                                    <tr>
                                                    <!-- $_SESSION['invoice_cart'][$row['id']]['quantity'] -->
                                                        <td><?php echo $values['item_name'] ?></td>
                                                        <td><input type="text" id="qty" size="5" name="qty[<?php echo $values['qty']; ?>]" value="<?php echo $values['qty']//echo $_SESSION['supply_cart'].[$values['item_name']].[$values['qty']] ?>"></td>
                                                        <td><?php echo $values['price'] ?></td>
                                                        <td><?php echo number_format($values['qty'] * $values['price'], 2) ?></td>
                                                        <td><?php

                                                            $query = $conn->query("SELECT id,name FROM coa WHERE id = '" . $values['acc'] . "'");
                                                            $row = mysqli_fetch_assoc($query);
                                                            echo $row['name'];
                                                            ?></td>
                                                        <td><a href="bill_create.php?action=delete&id=<?php echo $values['item_id'] ?>"><i class="fas fa-trash-alt"></i></a></td>
                                                        </tr>                                                           
                                                       
                                                    <?php

                                                            $total += ($values['qty'] * $values['price']);
                                                        }
                                                    ?>
                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
                                                        <th>Total Price: <?php echo number_format($total, 2); ?></th>
                                                        <td>&nbsp;</td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="3"></td>
                                                        <th>Discount: <span id="discount"></span></th>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <?php
                                                        } else {
                                                        echo "<td colspan='5'>No products in catalog</td>";
                                                        }

                                                    ?>
                                            </table>

                                            <div class="row col-md-12">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    
                                                </div>

                                                <div class="col-md-3">
                                                  
                                                </div>
                                                <div class="col-md-4">
                                                    
                                                </div>
                                            </div>
                                            <div class="row col-md-12">&nbsp;</div>

                                            <center><button type="submit" name="update_supply_cart" class="btn btn-primary" style="margin-left: 4em;">Update Catalog</button></center>
                                        </form>

                                        <button type="submit" name="clear_supply_cart" class="btn btn-primary" style="margin-left: 2em;">Clear</button>
                                        <button type="submit" name="save_supply" class="btn btn-primary" style="margin-left: 2em;">Save Invoice</button>
                                        <button type="submit" name="save_print" class="btn btn-primary" style="margin-left: 2em;">Save Invoice & Print</button>


                                    </div>

                                    <hr>
                                    <!-- /.card-body -->
                            </div>
                            </form>
                            <?php
                        if (isset($_SESSION['dont_close_alert'])){ ?>
                            <button hidden id="print" onclick="window.open('bill_print.php?bill_id=<?= $_SESSION['dont_close_alert'] ?>', '_blank');"></button>
                        <?php }
                        ?>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
        </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php
    include __DIR__ . '/../partials/footer.php';
    ?>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- Jquery Core Js -->
    <script src="js/jquery.min.js"></script>

    <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Supply</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php $item_id = rand(0, 100); ?>
                    <form method="post" action="bill_create.php?action=add&id=<?php echo $item_id; ?>">
                        <div class="form-group">

                            <label for="name" class="col-form-label">Name:</label>
                            <input type="text" name="name" class="form-control" required id="name">
                            <input type="hidden" name="id" value="<?php echo $item_id; ?>">
                        </div>
                        <div class="form-group">
                            <label for="qty" class="col-form-label">Quantity:</label>
                            <input type="number" name="qty" class="form-control" required id="qty">
                        </div>
                        <div class="form-group">
                            <label for="price" class="col-form-label">Price:</label>
                            <input type="number" name="price" class="form-control" required id="price">
                        </div>
                        <div class="form-group">
                            <label for="acc" class="col-form-label">Account:</label>
                            <select name="acc" id="acc" class="form-control" required>
                                <option value="" disabled selected>--Select Account--</option>
                                <?php
                                $query = $conn->query("SELECT id,name FROM coa ORDER BY name ASC");
                                while ($row = mysqli_fetch_assoc($query)) {
                                    echo  "<option value=" . $row['id'] . "> " . $row['name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add_supply">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- Jquery Core Js -->
    <script src="js/jquery.min.js"></script>
    <script>

$(document).ready(function(e){

   

    $('#suggestion_textbox').on('keypress', function(e) {
        var code = e.keyCode || e.which;
        if(code==13){
            e.preventDefault();
            let name = $("#suggestion_textbox").val();
            window.location.href = "bill_payment_create.php?page=supply_cart&action=add&name="+name;
        }
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

let total = 0
    $('input.select-invoice').on('click', function (e) {
        if ($(this).is(':checked')){
            total += parseFloat($(this).data('amount'))
            if ($('input.select-invoice').length == $('input.select-invoice:checked').length){
                $('.select-all#all').prop('checked',true)
            }
        }else{
            total -= parseFloat($(this).data('amount'))
            if ($('.select-all#all').is(':checked')){
                $('.select-all#all').prop('checked',false)
            }
        }
        $('.invoice-total').val(total.toFixed(2));
    });

    $('.select-all#all').click(function(e){
        var table= $(e.target).closest('table');
        $('td input:checkbox',table).prop('checked',this.checked);

        if ($(this).is(':checked')){
            total = 0;
            $('input.select-invoice').each(function () {
                total += parseFloat($(this).data('amount'))
            })
        }else{
            total = 0
        }

        $('.invoice-total').val(total.toFixed(2));
    });


    $(".alert:not(.dont-close)").fadeTo(8000, 500).slideUp(500, function(){
        $(".alert:not(.dont-close)").slideUp(500);
    });

    <?php
    if (isset($_SESSION['dont_close_alert'])){ ?>
    $('button#print').trigger('click');
    <?php unset($_SESSION['dont_close_alert']); }
?>
</script>
</body>
</html>
