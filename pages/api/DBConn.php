<?php

class DBConn
{
	
	   
	
	
	function syncInventory($data) {
		include('config.php');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
try  {
	$conn->begin_transaction();
foreach ($data as $item ){

	$productid = $item['productid'];
	$sold= $item['sold'];
	$salesmanid = $item['salesmanid'];
    $sql = " UPDATE stock_mobile set  sold= {$sold}  where store_owner={$salesmanid}  and product={$productid } ";
	// $sql .= " and date='{$date}'";
		$result = $conn->query($sql);
		
		if (mysqli_error($conn)) 
		{
			$conn->rollback();
			return mysqli_error($conn);
		}
	
   }
			  
			$conn->commit();
			return true;
		
}
catch (exception $e) {
	
     $conn->rollback();
	 return $e;
}
finally 
{
	$conn->close();
}	
	}
	
	
		function syncSales($data) {   
		include('config.php');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
try  {
	$conn->begin_transaction();
foreach ($data as $item ){

	
	$salesman= $item['salesmanid'];
 $date =  date("Y-m-d H:i:s", 	strtotime($item['date']));
  $doc_number =	 $item['doc_number'];
	$customerid= $item['customerid'];
	$pay_status= 1;

	$sql = "REPLACE INTO sales (salesman_id, doc_number, customerid, date) VALUES ($salesman,'{$doc_number}',{$customerid},   '{$date}' )";

	//	$sql = "Replace INTO new_sale (	 doc_number,product, quantity, ////sale_price, date, salesman, customer,pay_status) VALUES ( //'{$doc_number}',{$product_id}, {$quantity},{$sale_price}, '{$date}', //{$salesman},{$customerid},{$pay_status})";
		$result = $conn->query($sql);
		
		if (mysqli_error($conn)) 
		{    $conn->rollback();
			return mysqli_error($conn);
		}
		
}
		$conn->commit();
	return true;
		
}
catch (exception $e) {
     $conn->rollback();
	 return  $e;
} 
	finally 
{
	$conn->close();
}	
	}
	
	
	function syncCarts($data) {   
		include('config.php');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
try  {
	$conn->begin_transaction();
foreach ($data as $item ){
	$id = $item['id'];
	$product_id = $item['productID'];
	$quantity = $item['quantity'];
		$sale_price = $item['unitPrice'];
	
	$salesman= $item['salesmanid'];
 $date =  date("Y-m-d H:i:s", 	strtotime($item['date']));
  $doc_number =	 $item['doc_number'];
	$customerid= $item['customerid'];
	$pay_status= 1;


		$sql = "REPLACE INTO new_sale (doc_number,product, quantity, sale_price, date, salesman, customer,pay_status) VALUES ( '{$doc_number}',{$product_id}, {$quantity},{$sale_price}, '{$date}', {$salesman},{$customerid},{$pay_status})";
		$result = $conn->query($sql);
		
		if (mysqli_error($conn)) 
		{    $conn->rollback();
			return mysqli_error($conn);
		}
		
}
		$conn->commit();
	return true;
		
}
catch (exception $e) {
     $conn->rollback();
	 return  $e;
} 
	finally 
{
	$conn->close();
}	
	}
	
	
	
		function syncPayments($data) {
		include('config.php');
		

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
		try  {
	    $conn->begin_transaction();
	foreach ($data as $item ){
		$id = $item['id'];
		$amount=$item['amount'];
		$ref= isset($item['ref'])  ? $item['ref'] : '';
		$saleid=$item['saleid'];
		$mode=$item['mode'];
		$doc_number=$item['doc_number'];

		$outlet=$item['outlet'];
	 $date = date("Y-m-d h:i:s", 	strtotime($item['date']));
		$sql = "REPLACE INTO payments(doc_number, type, transaction_type, amount, mpesa_code, cheque_number, date) VALUES ('{$doc_number}', '{$mode}','Sale', {$amount}, '{$ref}','{$ref}', '{$date}')";
		
		$result = $conn->query($sql);
		if (mysqli_error($conn)) 
		{    $conn->rollback();
			return mysqli_error($conn);
		}
	}
		$conn->commit();
		
		return true;
		
}
catch (exception $e) {
     $conn->rollback();
	 return  $e;
}finally 
{
	$conn->close();
}	
	}
	

	
	function login($name, $pw) {
		include('config.php');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
		

        $hash=md5($pw);
		$sql = "SELECT * FROM users WHERE username='{$name}' AND password='{$hash}'";


		$result = $conn->query($sql);

		if (mysqli_num_rows($result) <= 0) {
			$conn->close();
			return "Invalid username or password or account is not activated. Please contact Admin for Support";
		}
		
		$i = 0;
		$retVal = array();
        while($row = mysqli_fetch_assoc($result)) {
            $retVal = $row;
			$i++;
        }	
		$conn->close();
		
		return $retVal;
	} 
	
		function getShops() {
		include('config.php');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
   
		$sql = "SELECT * FROM stores order by code ASC";
		$result = $conn->query($sql);

		$data = array();
		$i=0;
        while($row = mysqli_fetch_assoc($result)) {
           $data["shop_".$i] = $row;
			   $i++;
        }	
		$conn->close();
		
		return $data;
	} 
	
	
	
	
	

	function getStock( $salesmanid) {
		include('config.php');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
		
		$result = array();
		

		
		$sql = sprintf("SELECT * FROM stock_mobile WHERE  store_owner={$salesmanid} AND clear_status=0");
		

		$inventories = $conn->query($sql);
		$data = array();		
		if (mysqli_num_rows($inventories) > 0) {
			$i = 0;
            while($row = mysqli_fetch_assoc($inventories)) {
              $data[] = $row;
			   $i++;
            }
        }
		
		
		
		
		
		$conn->close();

		return $data;
	}
	
	function getStockAddition( $salesmanid) {
		include('config.php');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
		
		$result = array();
		

		
		$sql = sprintf("SELECT id, quantity_added as quantity_available, product FROM stock_mobile_additions WHERE  store_owner={$salesmanid} AND update_status=0");
		

		$inventories = $conn->query($sql);
		$data = array();		
		if (mysqli_num_rows($inventories) > 0) {
			$i = 0;
            while($row = mysqli_fetch_assoc($inventories)) {
              $data[] = $row;
			   $i++;
            }
        }
		
		
		
		
		
		$conn->close();

		return $data;
	}
	
	
	
	
	
	
	
	
	
	
	
	function getAll() {
		include('config.php');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
		
		$result = array();
		
		$sql = sprintf("SELECT * FROM product WHERE 1");
		$products = $conn->query($sql);		
		if (mysqli_num_rows($products) > 0) {
			$i = 0;
            while($row = mysqli_fetch_assoc($products)) {
               $result["product"][$i] = $row;
			   $i++;
            }
        }
		
		$sql = sprintf("SELECT * FROM inventory WHERE 1");
		$inventories = $conn->query($sql);		
		if (mysqli_num_rows($inventories) > 0) {
			$i = 0;
            while($row = mysqli_fetch_assoc($inventories)) {
               $result["inventory"][$i] = $row;
			   $i++;
            }
        }
		
		$sql = sprintf("SELECT * FROM cart WHERE 1");
		$carts = $conn->query($sql);		
		if (mysqli_num_rows($carts) > 0) {
			$i = 0;
            while($row = mysqli_fetch_assoc($carts)) {
               $result["cart"][$i] = $row;			   
			   $i++;
            }
        }
		
		$sql = sprintf("SELECT * FROM sale WHERE 1");
		$sales = $conn->query($sql);		
		if (mysqli_num_rows($sales) > 0) {
			$i = 0;
            while($row = mysqli_fetch_assoc($sales)) {
               $result["sale"][$i] = $row;
			   $i++;
            }
        }
		
		$conn->close();

		return $result;
	}
	
		function FlagAddition($data) {
		include('config.php');

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {
			die("Database Connection Failed.");
		}
try  {
	$conn->begin_transaction();
foreach ($data as $item ){

	$id = $item['id'];


    $sql ="UPDATE stock_mobile_additions set  update_status= 1 where id={$id}";

		$result = $conn->query($sql);
		
		if (mysqli_error($conn)) 
		{
			$conn->rollback();
			return mysqli_error($conn);
		}
	
   }
			  
			$conn->commit();
			return true;
		
}
catch (exception $e) {
	
     $conn->rollback();
	 return $e;
}
finally 
{
	$conn->close();
}	
	}
	
	
	
}
?>
