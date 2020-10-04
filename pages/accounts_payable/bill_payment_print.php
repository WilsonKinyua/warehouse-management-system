<?php
session_start();
include '../utils/conn.php';
if (!isset($_GET['supply_payment_id']) || empty($_GET['supply_payment_id'])){
    header("Location: bill_payment_create.php");
}
$paymentId = $_GET['supply_payment_id'];
$res = $conn->query("SELECT id FROM bill_payments where bill_payments.id='$paymentId' ");
if ($res->num_rows < 1){
    $_SESSION['error'] = 'The payment does not exists';
    header("Location: bill_payment_create.php");
    exit();
}

$py = $conn->query("SELECT ip.*,supplier.name, supplier.contact, i.reference as invoice_number, i.total as invoice_total
    FROM bills as i 
    LEFT JOIN
    bill_payments as ip ON  i.bill_payment_id= ip.id
    LEFT JOIN supplier ON i.id = supplier.id
    WHERE bill_payment_id='$paymentId'") or die($conn->error);

$payment = $py->fetch_assoc();
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
<div class="invoice-box rtl">
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
                            <span class="invoice-id"><?= $payment['reference'] ?></span>
                            <br>
                            <span class="t-invoice-created">Created</span>:
                            <span class="invoice-created"><?= date('Y M D H:i:s', strtotime($payment['tran_date'])) ?></span>
                            <br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="information-client">
                            <span class="t-invoice-to">INVOICE FROM</span><br>
                            <span id="client-name"><?= $payment['name'] ?></span><br>
                            <span id="client-address"><?= $payment['contact'] ?></span><br>
                            <!--                            <span id="client-town"></span><br>-->
                            <span id="client-country">Kenya</span><br>
                        </td>

                        <td class="information-company">
                            <span class="t-invoice-from">INVOICE TO</span><br>
                            <span id="company-name">Gemad Agencies Company Ltd</span><br>
                            <!--                            <span id="company-address"></span><br>-->
                            <span id="company-town">Nairobi</span><br>
                            <span id="company-country">Kenya</span><br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="invoice-items" cellpadding="0" cellspacing="0">
        <tr class="heading">
            <td><span class="t-item">Payment For</span></td>
            <td><span class="t-price"></span></td>
        </tr>
        <tr class="heading">
            <td><span class="t-item">Invoice</span></td>
            <td><span class="t-price">Subtotal</span></td>
        </tr>
        <?php
        mysqli_data_seek($py, 0);
        while ($row = $py->fetch_assoc()){ ?>
            <tr class="details">
                <td><span class="t-item"><?= $row['invoice_number'] ?></span></td>
                <td><span class="t-price"><?= abs((float)$row['invoice_total']) ?></span></td>
            </tr>
        <?php }
        ?>
    </table>

    <div class="invoice-summary">
        <div class="invoice-total">Total: <?= $payment['total'] ?></div>
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