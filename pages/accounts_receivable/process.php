<?php
// $conn = mysqli_connect('localhost','root','','premierdb') or die($conn->error);
include '../utils/conn.php';
$date = date("Y-m-d");
$username = "";

if (!isset($_SESSION['userId'])) {
    header('location: ../logout.php');
}else{
    $userId = $_SESSION['userId'];
}

// Normal Sale

if (isset($_POST['submit_add_sale'])) {
    $doc_number = $_POST['doc_number'];
    $products = $_POST['products'];
    $quantity = $_POST['quantity'];
    $shells = $_POST['shells'];
    $bottles = $_POST['bottles'];

    for ($i=0; $i < sizeof($products) ; $i++) {
        $product = $products[$i];
        $qty = $quantity[$i];
        $shell = $shells[$i];
        $bottle = $bottles[$i];
        // Calculate the total cost
        $price = $conn->query("SELECT price,category FROM products WHERE id='$product'") or die($conn->error);
        // Get Price for empties
        $total_cost = 0;
        $res = $price->fetch_assoc();
        $total_cost += ($res['price']*$qty);
        $category = $res['category'];
        $empties_cost = $conn->query("SELECT * FROM product_categories WHERE name='$category'") or die($conn->error);
        $result = $empties_cost->fetch_assoc();
        $bottles_cost= $result['bottle_price']*$bottle;
        $shell_cost = $result['shell_cost']*$shell;

        $total_cost += $bottles_cost;
        $total_cost += $shell_cost;

        $fin = $conn->query("INSERT INTO `sales`(`salesman_id`, `doc_number`, `sale_product`, `quantity`,`with_shell`, `sold_bottles`, `sale_price`,`date`) VALUES ('$userId','$doc_number','$product','$qty','$shell','$bottle','$total_cost','$date')") or die($conn->error);

        header("location: payment.php?doc=".$doc_number);
    }
}

$cheque_no_err = $mpesa_err ="";
$valid = true;

// Payment Submit
if (isset($_POST['payment_submit'])) {
    $total = trim($_POST['total']);
    $method = trim($_POST['method']);
    $chequeno = trim($_POST['chequeno']);
    $mpesacode = trim($_POST['mpesacode']);
    $doc=trim($_GET['doc']);

    if ($method == "cash") {
        $conn->query("INSERT INTO `payments`(`type`, `amount`, `status`) VALUES ('$method','$total','1')") or die($conn->error);
        header("location: print.php?payment=cash&type=sale&doc=".$doc."&total=".$total);
    }elseif ($method == "mpesa") {
        if (empty($mpesacode)) {
            $mpesa_err = "Mpesa Code is required";
            $valid = false;
        }
        elseif (strlen($mpesacode) != 10) {
            $mpesa_err = "Invalid Mpesa Code";
            $valid = false;
        }
        if ($valid) {
            $conn->query("INSERT INTO `payments`(`type`, `amount`, `mpesa_code`, `status`) VALUES ('$method','$total','$mpesacode','0')") or die($conn->error);
            header("location: print.php?type=sale&doc=".$doc."&total=".$total."&code=".$mpesacode."&payment=MPESA");
        }
    }else{
        if (empty($chequeno)) {
            $cheque_no_err = "Cheque is required";
            $valid = false;
        }
        elseif (strlen($chequeno) < 8) {
            $cheque_no_err = "Invalid Cheque Code";
            $valid = false;
        }
        if ($valid) {
            $conn->query("INSERT INTO `payments`(`type`, `amount`, `cheque_number`, `status`) VALUES ('$method','$total','$chequeno','0')") or die($conn->error);
            header("location: print.php?type=sale&doc=".$doc."&total=".$total."&code=".$chequeno."&payment=CHEQUE");
        }
    }
}

// Declare Shorts
$salesman = "";
$amount = 0;
if (isset($_GET['short'])) {

    $id = $_GET['short'];
    $users= $conn->query("SELECT name FROM users WHERE id='$id'") or die($conn->error);
    $user = $users->fetch_array();
    $salesman = $user['name'];
    foreach($conn->query("SELECT *, SUM(sale_price) FROM sales WHERE status='0' AND salesman_id='$id'") as $r) { $amount = $r['SUM(sale_price)']; }


}
if (isset($_POST['submit_dclr_short'])) {
    $id = 0;
    $name = trim($_POST['employee']);
    $amt = trim($_POST['amt']);
    $res = $conn->query("SELECT id FROM users WHERE name='$name'");
    while ($r=$res->fetch_assoc()){ $id = $r['id']; }
    $short = $amount-$amt;
    $listed_users = $conn->query("SELECT * FROM shorts WHERE user='$id' ") or die($conn->error);
    // var_dump($listed_users);
    // sleep(10000);
    if (mysqli_num_rows($listed_users)==1) {
        while ($row=$listed_users->fetch_assoc()) {
            $short += $row['sht_amt'];
            $conn->query("UPDATE shorts SET sht_amt='$short' WHERE user='$id'") or die($conn->error);
        }
    }else{
        $conn->query("INSERT INTO `shorts`(`user`, `reason`, `sht_amt`) VALUES ('$id','Sales short','$short')") or die($conn->error);
    }
    $conn->query("INSERT INTO `shorts_descriptions`(`employee`, `reason`, `amount`) VALUES ('$id','Sales short','$short')") or die($conn->error);
    $conn->query("UPDATE sales SET status=1 WHERE salesman_id='$id'")or die($conn->error);
    header("location: clearsales.php");
}

// Credit Sales
if (isset($_POST['submit_credit_sale'])) {
    $customer = $_POST['customer'];
    $doc_number =$_POST['doc_number'];
    $store = $_POST['store'];
    $products = $_POST['products'];
    $quantity = $_POST['quantity'];
    $shells = $_POST['shells'];
    $bottles = $_POST['bottles'];

    $total=0;

    for ($i=0; $i < sizeof($products) ; $i++) {
        $product = $products[$i];
        $qty = $quantity[$i];
        $shell = $shells[$i];
        $bottle = $bottles[$i];
        // Calculate the total cost
        $price = $conn->query("SELECT price,category FROM products WHERE id='$product'") or die($conn->error);
        // Get Price for empties
        $total_cost = 0;
        $res = $price->fetch_assoc();
        $total_cost += ($res['price']*$qty);
        $category = $res['category'];
        $empties_cost = $conn->query("SELECT * FROM product_categories WHERE name='$category'") or die($conn->error);
        $result = $empties_cost->fetch_assoc();
        $bottles_cost= $result['bottle_price']*$bottle;
        $shell_cost = $result['shell_cost']*$shell;

        $total_cost += $bottles_cost;
        $total_cost += $shell_cost;
        $total += $total_cost;

        $fin = $conn->query("INSERT INTO `credit_sales`(`customer_id`, `doc_number`, `store_id`, `product_id`, `quantity`, `shells`, `bottles`, `total_price`, `served_by`) VALUES ('$customer','$doc_number','$store','$product','$qty','$shell','$bottle','$total_cost','$userId')") or die($conn->error);

        header("location: print.php?type=credit&total=".$total."&doc=".$doc_number);
    }
}

if(!empty($_GET["action"])) {
    switch($_GET["action"]) {
        case "add":
            if(!empty($_POST["quantity"])) {
                $productByCode = $conn->query("SELECT * FROM products WHERE id='" . $_GET["code"] . "'");
                $itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));

                if(!empty($_SESSION["cart_item"])) {
                    if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
                        foreach($_SESSION["cart_item"] as $k => $v) {
                            if($productByCode[0]["code"] == $k) {
                                if(empty($_SESSION["cart_item"][$k]["quantity"])) {
                                    $_SESSION["cart_item"][$k]["quantity"] = 0;
                                }
                                $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                            }
                        }
                    } else {
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                    }
                } else {
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;
        case "remove":
            if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($_GET["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if(empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
    }
}

if(isset($_POST['update_cart'])){
    foreach($_POST['quantity'] as $key => $val) {
        if($val==0) {
            unset($_SESSION['invoice_cart'][$key]);
        }else{
            $_SESSION['invoice_cart'][$key]['quantity']=$val;
        }
    }
    header('location: request.php');
}

if(isset($_POST['clear_cart'])){
    unset($_SESSION['invoice_cart'], $_SESSION['invoice_customer']);
    header('location: request.php');
}

$cust_err = "";

$type_err = $acc_err = $code_err = $doc_err= $amt_err = "";
if(isset($_POST['save_sale'])){
    $doc_number = $_POST['doc_number'];
    // $supplier = $_POST['supplier'];
    $salesman = $_SESSION['userId'];
    $customer = $_POST['customer_name'];
    $account = $_POST['account_id'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $rollback = false;

    if (empty($customer)) {
        $cust_err = "A customer must be selected";
        $valid = false;
    }
    if (empty($doc_number)) {
        $doc_err = "Invoice id must be provided";
        $valid = false;
    }
    if (empty($account)){
        $acc_err = "An account must be selected";
        $valid = false;
    }
    if (mysqli_num_rows($conn->query("SELECT reference FROM invoices where reference='$doc_number'")) > 0){
        $doc_err = "Invoice id is already taken";
        $valid = false;
    }

    if ($valid){
        $sql="SELECT * FROM products WHERE id IN (";

        foreach($_SESSION['invoice_cart'] as $id => $value) {
            $sql.=$id.",";
        }

        $sql=substr($sql, 0, -1).") ORDER BY name ASC";
        $query=$conn->query($sql);
        $totalprice = 0;
        $subtotal = 0;
        $price = "";

        if (mysqli_num_rows($conn->query($sql)) < 1) {
            exit("No products on the cart");
        }

        $conn->begin_transaction();
        $date = date('Y-m-d H:i:s');

        $conn->query("INSERT INTO `invoices` (`tran_date`, `due_date`, `reference`, `description`, `total`, `customer_id`, `coa_id`, `salesman_id`, `status`) 
                                                        VALUES ('$date',". (empty($due_date) ? "NULL" : "'$due_date'") .",'$doc_number','$description', '$totalprice', '$customer','$account','$salesman', '0')") or die($conn->error);


        $invoice_id = $conn->insert_id;


        while($row=$query->fetch_assoc()){

            //subtotal
            if (isset($_SESSION['invoice_customer'])) {
                $cid = $_SESSION['invoice_customer'];
                $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE customers.id='$cid'") or die($conn->error);
                $array = $cust->fetch_array();
                $pricelist = $array['pricelist'];
                if ($pricelist==0) {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['distributor'];
                }elseif ($pricelist == 1) {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['stockist'];
                }elseif ($pricelist == 2) {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['stockist'];
                }elseif ($pricelist == 3) {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['wholesale'];
                }else {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['selling_cost'];
                }
            }

            $id= $row['id'];
            $quantity = $_SESSION['invoice_cart'][$row['id']]['quantity'];
            $rsql = $conn->query("SELECT * FROM products WHERE id='$id'") or die($conn->error);
            $nsql = $rsql->fetch_array();
            $nprod = $nsql['name'];
            //Check if the product exists
            $res = $conn->query("SELECT name, quantity_available FROM stock INNER JOIN products ON stock.product = products.id WHERE product='$id'") or die($conn->error);
            if (mysqli_num_rows($res) > 0) {
                // Update Stock
                $row = $res->fetch_array();
                if ($row['quantity_available']<$quantity) {
                    if ($query->num_rows <=1){
                        $conn->rollback();
                        $rollback = true;
                    }
                    $_SESSION['error'] .= $row['name'] .' Requested Quantity '.$quantity .' Above available stock  '.$row['quantity_available'].'. ';
                    continue;
                }else {
                    $totalprice += $subtotal;
                    $qty_a = $row['quantity_available'];
                    $qty_a -= $quantity;
                    $conn->query("UPDATE `stock` SET `quantity_available`='$qty_a' WHERE product='$id'") or die($conn->error);

                    $date = date('Y-m-d H:i:s');
                    $neg = 0 - (float) $subtotal;

                    //record to invoice lines
                    $conn->query("INSERT INTO `invoice_lines`( `product`, `quantity`, `line_amount`, `invoice_id`, `line_coa_id`) 
                                VALUES ('$id','$quantity','$neg','$invoice_id','510')") or die($conn->error);
                    $conn->commit();

                }

            }else{
                if ($query->num_rows <=1){
                    $conn->rollback();
                    $rollback = true;
                }
                // $message = '<div class="bg-danger">Some products are out of stock. Please check stocks</div>';
                // echo "<script type='text/javascript'>alert('$message');</script>";
                $_SESSION['error'] .= $nprod .' is out of stock Kindly Purchase ';
                continue;
            }
        }
    }
    $conn->query("UPDATE invoices set total ='$totalprice' WHERE id='$invoice_id'");
    if (!$rollback){
        $conn->commit();
        unset($_SESSION['invoice_customer'], $_SESSION['invoice_cart']);
        $_SESSION['success'] = 'The invoice was successfully recorded';
    }
    header('location: invoice_create.php');
    exit();
}


if(isset($_POST['save_print'])){
    $doc_number = $_POST['doc_number'];
    // $supplier = $_POST['supplier'];
    $salesman = $_SESSION['userId'];
    $customer = $_POST['customer_name'];
    $account = $_POST['account_id'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    $rollback = false;

    if (empty($customer)) {
        $cust_err = "A customer must be selected";
        $valid = false;
    }
    if (empty($doc_number)) {
        $doc_err = "Invoice id must be provided";
        $valid = false;
    }
    if (empty($account)){
        $acc_err = "An account must be selected";
        $valid = false;
    }
    if (mysqli_num_rows($conn->query("SELECT reference FROM invoices where reference='$doc_number'")) > 0){
        $doc_err = "Invoice id is already taken";
        $valid = false;
    }

    if ($valid){
        $sql="SELECT * FROM products WHERE id IN (";

        foreach($_SESSION['invoice_cart'] as $id => $value) {
            $sql.=$id.",";
        }

        $sql=substr($sql, 0, -1).") ORDER BY name ASC";
        $query=$conn->query($sql);
        $totalprice = 0;
        $subtotal = 0;
        $price = "";

        if (mysqli_num_rows($conn->query($sql)) < 1) {
            exit("No products on the cart");
        }

        $conn->begin_transaction();
        $date = date('Y-m-d H:i:s');

        $conn->query("INSERT INTO `invoices` (`tran_date`, `due_date`, `reference`, `description`, `total`, `customer_id`, `coa_id`, `salesman_id`, `status`) 
                                                        VALUES ('$date',". (empty($due_date) ? "NULL" : "'$due_date'") .",'$doc_number','$description', '$totalprice', '$customer','$account','$salesman', '0')") or die($conn->error);


        $invoice_id = $conn->insert_id;


        while($row=$query->fetch_assoc()){

            //subtotal
            if (isset($_SESSION['invoice_customer'])) {
                $cid = $_SESSION['invoice_customer'];
                $cust = $conn->query("SELECT * FROM customers INNER JOIN cust_groups ON customers.cust_group=cust_groups.id WHERE customers.id='$cid'") or die($conn->error);
                $array = $cust->fetch_array();
                $pricelist = $array['pricelist'];
                if ($pricelist==0) {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['distributor'];
                }elseif ($pricelist == 1) {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['stockist'];
                }elseif ($pricelist == 2) {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['stockist'];
                }elseif ($pricelist == 3) {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['wholesale'];
                }else {
                    $subtotal = $_SESSION['invoice_cart'][$row['id']]['quantity']*$row['selling_cost'];
                }
            }

            $id= $row['id'];
            $quantity = $_SESSION['invoice_cart'][$row['id']]['quantity'];
            $rsql = $conn->query("SELECT * FROM products WHERE id='$id'") or die($conn->error);
            $nsql = $rsql->fetch_array();
            $nprod = $nsql['name'];
            //Check if the product exists
            $res = $conn->query("SELECT name, quantity_available FROM stock INNER JOIN products ON stock.product = products.id WHERE product='$id'") or die($conn->error);
            if (mysqli_num_rows($res) > 0) {
                // Update Stock
                $row = $res->fetch_array();
                if ($row['quantity_available']<$quantity) {
                    if ($query->num_rows <=1){
                        $conn->rollback();
                        $rollback = true;
                    }
                    $_SESSION['error'] .= $row['name'] .' Requested Quantity '.$quantity .' Above available stock  '.$row['quantity_available'];
                    continue;
                }else {
                    $totalprice += $subtotal;
                    $qty_a = $row['quantity_available'];
                    $qty_a -= $quantity;
                    $conn->query("UPDATE `stock` SET `quantity_available`='$qty_a' WHERE product='$id'") or die($conn->error);

                    $date = date('Y-m-d H:i:s');
                    $neg = 0 - (float) $subtotal;

                    //record to invoice lines
                    $conn->query("INSERT INTO `invoice_lines`( `product`, `quantity`, `line_amount`, `invoice_id`, `line_coa_id`) 
                                VALUES ('$id','$quantity','$neg','$invoice_id','510')") or die($conn->error);
                    $conn->commit();

                }

            }else{
                if ($query->num_rows <=1){
                    $conn->rollback();
                    $rollback = true;
                }
                // $message = '<div class="bg-danger">Some products are out of stock. Please check stocks</div>';
                // echo "<script type='text/javascript'>alert('$message');</script>";
                $_SESSION['error'] .= $nprod .' is out of stock Kindly Purchase';
                continue;
            }
        }
    }
    $conn->query("UPDATE invoices set total ='$totalprice' WHERE id='$invoice_id'");
    if (!$rollback){
        $conn->commit();
        unset($_SESSION['invoice_customer'], $_SESSION['invoice_cart']);
        $_SESSION['success'] = 'The invoice was successfully recorded';
        $_SESSION['dont_close_alert'] = $invoice_id;
        header('location: invoice_create.php');
        exit();
    }
    header('location: invoice_create.php');
    exit();

}
?>
