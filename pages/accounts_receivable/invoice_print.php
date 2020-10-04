<?php
session_start();
include '../utils/conn.php';
if (!isset($_GET['invoice_id']) || empty($_GET['invoice_id'])){
    header("Location: invoice_create.php");
}
$invoiceId = $_GET['invoice_id'];
$res = $conn->query("SELECT invoices.*, c.cust_name, c.cust_contact FROM invoices LEFT JOIN customers as c ON invoices.customer_id = c.id where invoices.id='$invoiceId' ");
if ($res->num_rows < 1){
    $_SESSION['error'] = 'The invoice does not exists';
    header("Location: invoice_create.php");
    exit();
}


$productsPurchased = $conn->query("SELECT invoices.*, invoice_lines.*, products.name as product_name  FROM invoice_lines LEFT JOIN 
    invoices ON invoice_lines.invoice_id=invoices.id 
    LEFT JOIN products on invoice_lines.product = products.id
    where invoice_lines.invoice_id='$invoiceId'") or die($conn->error);

$invoice = $res->fetch_assoc();
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <!-- Dependencies -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <!-- Invoice -->
    <link rel="stylesheet" href="css/invoice.css">
</head>

<body>
<div class="invoice-box">
    <button class="print-button"><span class="print-icon"></span></button>
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <span class="t-invoice"></span>
                        </td>

                        <td>
                            <span class="t-invoice"></span>
                            <span class="invoice-id"><?= $invoice['reference'] ?></span>
                            <br>
                            <span class="t-invoice-created">Created</span>:
                            <span class="invoice-created"><?= date('Y M D H:i:s', strtotime($invoice['tran_date'])) ?></span>
                            <br>
                            <?php
                            if ($invoice['due_date']){ ?>
                                <span class="t-invoice-due">Due</span>:
                                <span class="invoice-due"><?= $invoice['due_date'] ?></span>
                                <br>
                            <?php }
                            ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="information-company">
                            <span class="t-invoice-from">INVOICE FROM</span><br>
                            <span id="company-name">Gemad Agencies Ltd</span><br>
<!--                            <span id="company-address"></span><br>-->
                            <span id="company-town">Nairobi</span><br>
                            <span id="company-country">Kenya</span><br>
                        </td>

                        <td class="information-client">
                            <span class="t-invoice-to">INVOICE TO</span><br>
                            <span id="client-name"><?= $invoice['cust_name'] ?></span><br>
                            <span id="client-address"><?= $invoice['cust_contact'] ?></span><br>
<!--                            <span id="client-town"></span><br>-->
                            <span id="client-country">Kenya</span><br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="invoice-payment" cellpadding="0" cellspacing="0">
        <tr class="heading">
            <td>
                <span class="t-payment-method">Payment</span>
            </td>
        </tr>

        <tr class="details">
            <td>
                <?php
                if (!empty($invoice['invoice_payment_id'])){ ?>
                    <span class="payment-method"></span><br>
                    <span class="payment-details"></span>
                <?php }else{ ?>
                    Not Paid
                <?php }
                ?>
            </td>
        </tr>
    </table>

    <table class="invoice-items" cellpadding="0" cellspacing="0">
        <tr class="heading">
            <td style="width: 33%; text-align: center;"><span class="t-item">Product</span></td>
            <td style="width: 33%; text-align: center;"><span class="t-item">Quantity</span></td>
            <td style="width: 33%; text-align: center;"><span class="t-price">Subtotal</span></td>
        </tr>
        <?php
        foreach ($productsPurchased as $product){ ?>
            <tr class="details">
                <td style="width: 33%; text-align: center;"><span class="t-item"><?= $product['product_name'] ?></span></td>
                <td style="width: 33%; text-align: center;"><span class="t-item"><?= $product['quantity'] ?></span></td>
                <td style="width: 33%; text-align: center;"><span class="t-price"><?= abs((float)$product['line_amount']) ?></span></td>
            </tr>
        <?php }
        ?>
    </table>

    <div class="invoice-summary">
        <div class="invoice-total">Total: <?= $invoice['total'] ?></div>
        <div class="invoice-final"></div>
        <div class="invoice-exchange"></div>
    </div>
</div>
<!-- Dependencies -->
<script src="js/jquery.min.js"></script>
<!-- Invoice -->
<!--<script src="invoice.js"></script>-->
<script>
    $('.print-button').on('click', function () {
        window.print()
    })
</script>
</body>
</html>