<?php
include '../../utils/conn.php';
if(isset($_POST["id"]))
{
 $query = "DELETE FROM products WHERE id = '".$_POST["id"]."'";
 if(mysqli_query($conn, $query))
 {
  echo 'Data Deleted';
 }
}
?>
