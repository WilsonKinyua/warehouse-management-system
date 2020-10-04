<?php
session_start();
include 'payment-process.php';
$message = "";

if(isset($_POST['clear_cart'])){
    unset($_SESSION['payment_customer']);
    header('location: payment_create.php');
}

if (isset($_GET['customer'])) {
    $_SESSION['payment_customer'] = $_GET['customer'];
}

?>
<?php
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


    <script type="text/javascript">
        function pickCost() {
            var customer = document.getElementById('customer').value;
            window.location.href = "payment_create.php?customer="+customer;
        }
    </script>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->

        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <?php
                        if (isset($_SESSION['error'])){ ?>
                            <div class="alert alert-danger alert-dismissible fade show <?= isset($_SESSION['dont_close_alert'])? 'dont-close':'' ?>" role="alert">
                                <?= $_SESSION['error'] ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php unset($_SESSION['error']); }
                        if (isset($_SESSION['success'])){ ?>
                            <div class="alert alert-success alert-dismissible fade show <?= isset($_SESSION['dont_close_alert'])? 'dont-close':'' ?>" role="alert">
                                <?= $_SESSION['success'] ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php unset($_SESSION['success']); }
                        ?>
                    </div>
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Make an Invoice Sale</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form role="form" action="payment_create.php" method="POST">
                                <input type="hidden" name="salesman" value="<?php echo $username; ?>">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <?php
                                            $query = $conn->query('SELECT id FROM invoice_payments ORDER BY id DESC LIMIT 1');
                                            $last = $query->fetch_assoc();
                                            $lastId = isset($last['id']) ? (int)$last['id'] : 0;
                                            $docId = str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
                                            ?>
                                            <div class="col-md-4">
                                                <label>Receipt Number: </label>
                                                <input type="text" name="doc_number" value="PY-<?php echo $docId; ?>" class="form-control" readonly>
                                                <span style="color: red;"><?php echo $doc_err; ?></span>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="customer_name">Customer: </label>
                                                <select class="form-control" name="customer_name" id="customer" onchange="pickCost()">
                                                    <option value="" disabled selected>--Select Customer--</option>
                                                    <?php

                                                    ?>
                                                    <?php if ($group != 'Salesman'){
                                                        $query = "SELECT id,cust_name FROM customers ORDER By cust_name ASC";
                                                        $res = $conn->query($query) or die($conn->error);
                                                        while($row = $res->fetch_assoc()){
                                                            if (isset($_GET['customer'])) {
                                                                $id = $_GET['customer'];
                                                                if ($row['id'] == $id) {
                                                                    echo  "<option value=".$row['id']." selected> ".$row['cust_name']."</option>";
                                                                }else{
                                                                    echo "<option value=".$row['id']."> ".$row['cust_name']."</option>";
                                                                }
                                                            }
                                                            elseif ((isset($_SESSION['payment_customer']) && $_SESSION['payment_customer'] == $row['id'])){
                                                                echo  "<option value=".$row['id']." selected> ".$row['cust_name']."</option>";
                                                            }
                                                            else{
                                                                echo "<option value=".$row['id']."> ".$row['cust_name']."</option>";
                                                            }
                                                        }}
                                                    ?>
                                                </select>
                                                <span style="color:red;"><?php echo $cust_err; ?></span>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Payment Date: </label>
                                                <input type="date" name="sale_date" value="<?php echo date("Y-m-d"); ?>" class="form-control" readonly>
                                            </div>
                                            <div class="col-md-12">
                                                <?php echo $message; ?>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Payment Type: </label>
                                                <select class="form-control" name="payment_type">
                                                    <option value="">Select an option</option>
                                                    <option value="mpesa">Mpesa</option>
                                                    <option value="cheque">Cheque</option>
                                                    <option value="cash">Cash</option>
                                                </select>
                                                <span style="color: red;"><?php echo $type_err; ?></span>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Account: </label>
                                                <select class="form-control" name="account_id">
                                                    <?php
                                                    $res = $conn->query("SELECT id, name FROM coa  ORDER BY name ASC") or die($conn->error);
                                                    while ($row= $res->fetch_assoc()){ ?>
                                                        <option value="<?= $row['id'] ?>" <?= $row['id'] == 110 ? 'selected': '' ?> ><?= $row['name'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                                <span style="color: red;"><?php echo $acc_err; ?></span>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Description</label>
                                                <textarea name="description" placeholder="Invoice description" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <form method="post" action="invoice_create.php?page=cart">
                                        <span style="color: red;"><?php echo $inv_err; ?></span>
                                        <table class="table table-bordered table-striped col-md-11" style="width: 100%; margin-left: 2em;">
                                            <tr>
                                                <th>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input select-all" id="all">
                                                        <label class="custom-control-label font-weight-normal" for="all"></label>
                                                    </div>
                                                </th>
                                                <th>Invoice No</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                            </tr>

                                            <?php
                                            if (!isset($_SESSION['payment_customer']) || empty($_SESSION['payment_customer'])) {
                                                echo "<td colspan='6'>No invoices in catalog</td>";
                                            }else{
                                                $cust_id =  $_SESSION['payment_customer'];
                                                $res = $conn->query("SELECT * FROM invoices where customer_id ='$cust_id' AND NOT status = 1");
                                                if ($res->num_rows > 0){
                                                    $x = 0;
                                                    while ($row = $res->fetch_assoc()){ $x++; ?>
                                                        <tr class="" >
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input name="invoices[]" type="checkbox" class="custom-control-input select-invoice" value="<?= $row['id'] ?>" data-amount="<?= $row['total'] ?>" id="<?= $row['id'] ?>">
                                                                    <label class="custom-control-label font-weight-normal" for="<?= $row['id'] ?>"><?= $x ?></label>
                                                                </div>
                                                            </td>
                                                            <td><?= $row['reference'] ?></td>
                                                            <td><?= $row['total'] ?></td>
                                                            <td><?= $row['tran_date'] ?></td>
                                                            <td><?= $row['description'] ?></td>
                                                            <td><?= $row['status'] == 1 ? 'Paid' : 'Not Paid'  ?></td>
                                                        </tr>
                                                    <?php }
                                                }else{
                                                    echo "<td colspan='6'>Customer has no invoices</td>";
                                                }
                                            } ?>
                                        </table>
                                        <div class="row col-md-12 justify-content-end">
                                            <div class="col-md-4">
                                                <div class="form-row">
                                                    <label for="" class="col-3 text-center pt-2">Total</label>
                                                    <input type="text" readonly value="0" class="form-control col-8 invoice-total">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row col-md-12">&nbsp;</div>
                                    </form>

                                    <button type="submit" name="clear_cart" class="btn btn-primary" style="margin-left: 2em;">Clear</button>
                                    <button type="submit" name="save_payment" class="btn btn-primary" style="margin-left: 2em;">Save Payment</button>
                                    <button type="submit" name="save_print" class="btn btn-primary" style="margin-left: 2em;">Save Payment & Print</button>

                                </div>

                                <hr>
                                <!-- /.card-body -->
                        </div>
                        </form>
                        <?php
                        if (isset($_SESSION['dont_close_alert'])){ ?>
                            <button hidden id="print" onclick="window.open('payment_print.php?payment_id=<?= $_SESSION['dont_close_alert'] ?>', '_blank');"></button>
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
include __DIR__.'/../partials/footer.php';
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

<script>

    $(document).ready(function(e){

        $("#suggestion_textbox").autocomplete({
            source:'search.php'
        });

        $('#suggestion_textbox').on('keypress', function(e) {
            var code = e.keyCode || e.which;
            if(code==13){
                e.preventDefault();
                let name = $("#suggestion_textbox").val();
                window.location.href = "invoice_create.php?page=products&action=add&name="+name;
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
    $('form').on('submit', function (e) {
        // e.preventDefault();
        //
        // console.log($(this).serializeArray())
    })
</script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
</body>
</html>

