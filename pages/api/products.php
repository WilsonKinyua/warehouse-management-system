<?php
include '../utils/conn.php';
$customers = $conn->query("SELECT * FROM products  order by name asc") or die($conn->error);
$json = array();
while ($row = $customers->fetch_assoc()) {
  $data[] = $row;
}

header('Content-Type: application/json ; charset=utf-8');
try {
    $json = json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE|JSON_INVALID_UTF8_IGNORE);
  echo $json;
} catch (JsonException $e) {
echo "Could not encrypt the data". $e;
}


// Human-friendly message

//echo json_encode( $json, JSON_PRETTY_PRINT);
 ?>