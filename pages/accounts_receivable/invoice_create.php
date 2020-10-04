<?php
session_start();
include 'process.php';
$message = "";
if(isset($_POST['update_cart'])){
    foreach($_POST['quantity'] as $key => $val) {
        if($val==0) {
            unset($_SESSION['invoice_cart'][$key]);
        }else{
            $_SESSION['invoice_cart'][$key]['quantity']=$val;
        }
    }
    header('location: invoice_create.php');
}

if(isset($_POST['clear_cart'])){
    unset($_SESSION['invoice_cart']);
    header('location: invoice_create.php');
}

if (isset($_GET['customer'])) {
    $_SESSION['invoice_customer'] = $_GET['customer'];
}


if(isset($_GET['action']) && $_GET['action']=="add"){
    if ($_GET['name']) {
        $name = $_GET['name'];
        $product = $conn ->query("SELECT id FROM products WHERE name='$name'") or die($conn->error);
        $res = $product->fetch_array();
        $id = $res['id'];
    }else{
        $id=intval($_GET['id']);
    }

    if (isset($_GET['customer'])) {
        $cid = $_GET['customer'];
        $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE id='$cid'") or die($conn->error);
        $array = $cust->fetch_array();
        $pricelist = $array['pricelist'];
        if ($pricelist==1) {
            $price = 'wholesale';
        }elseif ($pricelist == 2) {
            $price = 'stockist';
        }elseif ($pricelist == 3) {
            $price = 'distributor';
        }elseif ($pricelist == 4) {
            $price = 'retail';
        }else {
            $price = 'retail';
        }
    }

    if(isset($_SESSION['invoice_cart'][$id])){
        $_SESSION['invoice_cart'][$id]['quantity']++;
    }else{
        $sql_s="SELECT * FROM products WHERE id={$id}";
        $query_s=$conn->query($sql_s);
        if(mysqli_num_rows($query_s)!=0){
            $row_s=$query_s->fetch_array();
            $_SESSION['invoice_cart'][$row_s['id']]=array(
                "quantity" => 1,
                "price" => $row_s['price']
            );
        }else{
            $message="This product id it's invalid!";
        }

    }
    header('location: invoice_create.php');
}
if(isset($_GET['action']) && $_GET['action']=="remove"){

    $id=intval($_GET['id']);
    unset($_SESSION['invoice_cart'][$id]);
}


if(isset($_GET['page'])){

    $pages=array("products", "cart");

    if(in_array($_GET['page'], $pages)) {

        $_page=$_GET['page'];

    }else{

        $_page="products";

    }

}else{

    $_page="products";

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
            window.location.href = "invoice_create.php?customer="+customer;
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
                            <form role="form" action="invoice_create.php" method="POST">
                                <input type="hidden" name="salesman" value="<?php echo $username; ?>">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <?php
                                            $query = $conn->query('SELECT id FROM invoices ORDER BY id DESC LIMIT 1');
                                            $last = $query->fetch_assoc();
                                            $lastId = isset($last['id']) ? (int)$last['id'] : 0;
                                            $docId = str_pad($lastId + 1, 6, '0', STR_PAD_LEFT);
                                            ?>
                                            <div class="col-md-4">
                                                <label>Invoice Number: </label>
                                                <input type="text" name="doc_number" value="INV-<?php echo $docId; ?>" class="form-control" readonly>
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
                                                            elseif ((isset($_SESSION['invoice_customer']) && $_SESSION['invoice_customer'] == $row['id'])){
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
                                                <label>Sale Date: </label>
                                                <input type="date" name="sale_date" value="<?php echo date("Y-m-d"); ?>" class="form-control" readonly>
                                            </div>
                                            <div class="col-md-12">
                                                <?php echo $message; ?>
                                            </div>
                                            <div class="col-md-2">
                                                &nbsp;
                                            </div>
                                            <div class="col-md-8">
                                                <label>Search for A Product:</label>
                                                <input class="form-control" type="text" id="suggestion_textbox" />
                                            </div>
                                            <div class="col-md-2">
                                                &nbsp;
                                            </div>
                                            <script src="js/jquery.min.js"></script>
                                            <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
                                            <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
                                            <script>

                                                $(document).ready(function(e){

                                                    $("#suggestion_textbox").autocomplete({
                                                        source:'search.php'
                                                    });

                                                });
                                                $('#suggestion_textbox').on('keypress', function(e) {
                                                    var code = e.keyCode || e.which;
                                                    if(code==13){
                                                        e.preventDefault();
                                                        let name = $("#suggestion_textbox").val();
                                                        window.location.href = "invoice_create.php?page=products&action=add&name="+name;
                                                    }
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <form method="post" action="invoice_create.php?page=cart">
                                        <table class="table table-bordered table-striped col-md-11" style="width: 100%; margin-left: 2em;">
                                            <tr>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Subtotal</th>
                                                <th>Remove</th>
                                            </tr>

                                            <?php
                                            if (!isset($_SESSION['invoice_cart']) || empty($_SESSION['invoice_cart'])) {
                                                echo "<td colspan='5'>No products in catalog</td>";
                                            }else{
                                                $price=0;
                                                if (isset($_GET['customer'])) {
                                                    $cid = $_GET['customer'];
                                                    $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE customers.id='$cid'") or die($conn->error);
                                                    $array = $cust->fetch_array();
                                                    $pricelist = $array['pricelist'];
                                                    if ($pricelist==1) {
                                                        $price = 'wholesale';
                                                    }elseif ($pricelist == 2) {
                                                        $price = 'stockist';
                                                    }elseif ($pricelist == 2) {
                                                        $price = 'distributor';
                                                    }elseif ($pricelist == 3) {
                                                        $price = 'retail';
                                                    }else {
                                                        $price = 'retail';
                                                    }
                                                }

                                                $sql="SELECT * FROM products WHERE id IN (";

                                                foreach($_SESSION['invoice_cart'] as $id => $value) {
                                                    $sql.=$id.",";
                                                }

                                                $sql=substr($sql, 0, -1).") ORDER BY name ASC";
                                                $query=$conn->query($sql);
                                                $totalprice=0;
                                                while($row=$query->fetch_assoc()){
                                                    if (isset($_SESSION['invoice_customer'])) {
                                                        $cid = $_SESSION['invoice_customer'];
                                                        $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE customers.id='$cid'") or die($conn->error);
                                                        $array = $cust->fetch_array();
                                                        $pricelist = $array['pricelist'];
                                                        if ($pricelist==1) {
                                                            $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['wholesale'];
                                                            $totalprice += $subtotal;
                                                        }elseif ($pricelist == 2) {
                                                            $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['stockist'];
                                                            $totalprice += $subtotal;
                                                        }elseif ($pricelist == 3) {
                                                            $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['distributor'];
                                                            $totalprice += $subtotal;
                                                        }elseif ($pricelist == 4) {
                                                            $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['retail'];
                                                            $totalprice += $subtotal;
                                                        }else {
                                                            $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['retail'];
                                                            $totalprice += $subtotal;
                                                        }
                                                    }else{
                                                        $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['retail'];
                                                        $totalprice += $subtotal;
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><input type="text" name="quantity[<?php echo $row['id'] ?>]" size="5" value="<?php echo $_SESSION['invoice_cart'][$row['id']]['quantity'] ?>" /></td>
                                                        <?php
                                                        if (isset($_GET['customer'])) {
                                                            $cid = $_GET['customer'];
                                                            if ($cid =="-1") {
                                                                echo '<script>window.location.href="invoice_create.php"</script>';
                                                            }
                                                            $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE customers.id='$cid'") or die($conn->error);
                                                            $array = $cust->fetch_array();
                                                            $pricelist = $array['pricelist'];
                                                            if ($pricelist==1) {
                                                                echo "<td>Kshs. ".$row['wholesale']."</td>";
                                                                echo "<td>Kshs.".$_SESSION['invoice_cart'][$row['id']]['quantity']*$row['wholesale']."</td>";
                                                            }elseif ($pricelist == 2) {
                                                                echo "<td>Kshs.". $row['stockist']."</td>";
                                                                echo "<td>Kshs.".$_SESSION['invoice_cart'][$row['id']]['quantity']*$row['stockist']."</td>";
                                                            }elseif ($pricelist == 3) {
                                                                echo "<td>Kshs. ". $row['distributor']."</td>";
                                                                echo "<td>Kshs.".$_SESSION['invoice_cart'][$row['id']]['quantity']*$row['distributor']."</td>";

                                                            }elseif ($pricelist == 4) {
                                                                echo "<td>Kshs. ".$row['retail']."</td>";
                                                                echo "<td>Kshs.".$_SESSION['invoice_cart'][$row['id']]['quantity']*$row['retail']."</td>";

                                                            }else {
                                                                echo "<td>Kshs. ".$row['retail']."</td>";
                                                                echo "<td>Kshs.".$_SESSION['invoice_cart'][$row['id']]['quantity']*$row['retail']."</td>";
                                                            }
                                                        }else{
                                                            echo "<td>Kshs. ".$row['retail']."</td>";
                                                            echo "<td>Kshs.".$_SESSION['invoice_cart'][$row['id']]['quantity']*$row['retail']."</td>";
                                                        }
                                                        ?>
                                                        <td><a href="invoice_create.php?page=products&action=remove&id=<?php echo $row['id'] ?>"><i class="fas fa-trash-alt"></i></a></td>
                                                    </tr>
                                                <?php }?>
                                                <tr><td colspan="3"></td>
                                                    <th>Total Price: <?php echo $totalprice ?></th>
                                                    <td>&nbsp;</td>

                                                </tr>

                                                <tr><td colspan="3"></td>
                                                    <th>Discount: <span id="discount"></span></th>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            <?php } ?>
                                        </table>

                                        <div class="row col-md-12">
                                            <div class="col-md-1"></div>
                                            <div class="col-md-4">
                                                <label for="">Account: </label>
                                                <select class="form-control" name="account_id">
                                                    <?php
                                                    $res = $conn->query("SELECT id, name FROM coa  ORDER BY name ASC") or die($conn->error);
                                                    while ($row= $res->fetch_assoc()){ ?>
                                                        <option value="<?= $row['id'] ?>" <?= $row['id'] == 120 ? 'selected': '' ?> ><?= $row['name'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                                <span style="color: red;"><?php echo $acc_err; ?></span>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="">Due Date: </label>
                                                <input type="date" name="due_date" min="<?php echo date("Y-m-d"); ?>" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="">Description</label>
                                                <textarea name="description" placeholder="Invoice description" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                        <div class="row col-md-12">&nbsp;</div>

                                        <center><button type="submit" name="update_cart" class="btn btn-primary" style="margin-left: 4em;">Update Catalog</button></center>
                                    </form>

                                    <button type="submit" name="clear_cart" class="btn btn-primary" style="margin-left: 2em;">Clear</button>
                                    <button type="submit" name="save_sale" class="btn btn-primary" style="margin-left: 2em;">Save Invoice</button>
                                    <button type="submit" name="save_print" class="btn btn-primary" style="margin-left: 2em;">Save Invoice & Print</button>

                                </div>

                                <hr>
                                <!-- /.card-body -->
                        </div>
                        </form>
                        <?php
                        if (isset($_SESSION['dont_close_alert'])){ ?>
                            <button hidden id="print" onclick="window.open('invoice_print.php?invoice_id=<?= $_SESSION['dont_close_alert'] ?>', '_blank');"></button>
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
    $(".alert:not(.dont-close)").fadeTo(8000, 500).slideUp(500, function(){
        $(".alert:not(.dont-close)").slideUp(500);
    });

    <?php
    if (isset($_SESSION['dont_close_alert'])){ ?>
        $('button#print').trigger('click');
    <?php unset($_SESSION['dont_close_alert']); }
    ?>
</script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
</body>
</html>

