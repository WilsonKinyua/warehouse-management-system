<?php
//call the FPDF library
require('../fpdf/fpdf.php');
require '../utils/conn.php';

session_start();
if (!isset($_SESSION['userId'])) {
  header('location: ../utils/logout.php');
}else{
  $userId = $_SESSION['userId'];
}
// $conn = mysqli_connect('localhost','root','','premierdb') or die($conn->error);

//A4 width : 219mm
//default margin : 10mm each side
//writable horizontal : 219-(10*2)=189mm
$doc = 'DOC-SALE'.round(microtime(true)*1000);
//create pdf object
$pdf = new FPDF('P','mm','A4');
//add new page
$pdf->AddPage();
//output the result
//set font to arial, bold, 14pt
$pdf->SetFont('Arial','B',14);

//Cell(width , height , text , border , end line , [align] )

$pdf->Cell(130 ,5,'PREMIERSOFT .CO',0,0);
$pdf->Cell(59 ,5,'INVOICE',0,1);//end of line

//set font to arial, regular, 12pt
$pdf->SetFont('Arial','',12);

$pdf->Cell(130 ,5,'[Street Address]',0,0);
$pdf->Cell(59 ,5,'',0,1);//end of line

$pdf->Cell(130 ,5,'[City, Country, ZIP]',0,0);
$pdf->Cell(25 ,5,'Date',0,0);
$pdf->Cell(34 ,5,date("Y/m/d") ,0,1);//end of line

$pdf->Cell(130 ,5,'Phone [+12345678]',0,0);
$pdf->Cell(25 ,5,'Invoice #',0,0);
$pdf->Cell(34 ,5,substr($doc, 4),0,1);//end of line

$pdf->Cell(130 ,5,'Fax [+12345678]',0,0);
$pdf->Cell(25 ,5,'Employee ID',0,0);
$pdf->Cell(34 ,5,"#",0,1);//end of line

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line


//billing address
$pdf->Cell(100 ,5,'Bill to:',0,1);//end of line

//add dummy cell at beginning of each line for indentation
$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'[Name]',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'Payment: ',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'Code: ',0,1);

$pdf->Cell(10 ,5,'',0,0);
$pdf->Cell(90 ,5,'Amount: ',0,1);

//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line



//invoice contents
$pdf->SetFont('Arial','B',12);

$pdf->Cell(10 ,8,'#',1,0);
$pdf->Cell(60 ,8,'Product',1,0);
$pdf->Cell(25 ,8,'Quantity',1,1);//end of line

$pdf->SetFont('Arial','',12);


$sql="SELECT * FROM products WHERE id IN (";

        foreach($_SESSION['cart'] as $id => $value) {
            $sql.=$id.",";
        }

        $sql=substr($sql, 0, -1).") ORDER BY name ASC";
        $query=$conn->query($sql);
        $totalprice = 0;
        $price = "";
        if (mysqli_num_rows($sql) < 1) {
          echo "No products in the cart";
          exit("No products in the cart")
        }else{
          while($row=$query->fetch_assoc()){
            $subtotal = $_SESSION['cart'][$row['id']]['quantity']*$row['price'];
            $totalprice += $subtotal;
            $pdf->Cell(10 ,8,"#",1,0);
            $pdf->Cell(60 ,8,$row['name']." (".$row['category']." )",1,0);
            $pdf->Cell(25 ,8,$_SESSION['cart'][$row['id']]['quantity'],1,1);
          }

        }




//make a dummy empty cell as a vertical spacer
$pdf->Cell(189 ,10,'',0,1);//end of line
$pdf->Cell(110 ,8,'Served By:',0,1);
$pdf->SetFont('Arial','',12);

// Get the session user
$res = $conn->query("SELECT name FROM users WHERE id='$userId' ") or die($conn->error);
while ($row= $res->fetch_assoc()) {
	$pdf->Cell(25 ,8,$row['name'],0,1);
}



$pdf->Output();
?>
