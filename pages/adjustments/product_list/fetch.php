
<?php
//fetch.php
include '../../utils/conn.php';
$columns = array('product','quantity_available');

$query = "SELECT  products.name,stock.quantity_available, stock.id as sid FROM stock INNER JOIN products ON stock.product=products.id ORDER BY products.name";


// $query .= " INNER JOIN products ON stock.product=products.id";
$number_filter_row = mysqli_num_rows(mysqli_query($conn, $query));

$result = mysqli_query($conn, $query);
$length = mysqli_num_rows($result);
$data = array();

while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 $sub_array[] = '<div class="update" data-id="'.$row["sid"].'" data-column="name">' . $row["name"] .'</div>';
 $sub_array[] = '<div contenteditable class="update" data-id="'.$row["sid"].'" data-column="quantity_available">' . $row["quantity_available"] . '</div>';
 $data[] = $sub_array;
}


$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  $length,
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

?>
