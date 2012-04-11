<?php

//*** Fall 2011 Project -  sales systems
//*** rating page

session_start();
include("mysql.php");

$item_id = $_REQUEST["item_id"];
$purchase_id = $_REQUEST["purchase_id"];
$from = $_REQUEST["from"];
$rate = $_REQUEST["rate"];
$seller = $_REQUEST["seller"];
$comment = $_REQUEST["comment"];
$buyer = $_REQUEST["buyer"];

if($from == "buyer") {
	//*** check for illegal access
	if($_SESSION["ID"] != trim($buyer))
		echo"<script>location.href = '../default.php'</script>";

	$query1 = "update Seller set rating = $rate, comment = '$comment', status = 'rated' where purchase_id = $purchase_id";
	executeSQL2($query1);
} else {
	//*** check for illegal access
	if($_SESSION["ID"] != trim($seller))
		echo"<script>location.href = '../default.php'</script>";

	$query1 = "update Buyer set rating = $rate, comment = '$comment', status = 'rated' where purchase_id = $purchase_id";
	executeSQL2($query1);
}

print "OK";

?>