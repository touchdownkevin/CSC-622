<?php

//*** Fall 2011 Project -  sales systems
//*** bidding

include("mysql.php");

//*** illegal access
if(!isset($_REQUEST["item_id"]))
	echo"<script>location.href = '../default.php'</script>";

$item_id = $_REQUEST["item_id"];
$bid_price = $_REQUEST["bid_price"];
$login_id = $_REQUEST["login_id"];

$query1 = "insert into Bid values('$login_id', '$item_id', '$bid_price', NOW(), 'active')";
executeSQL2($query1);

print "OK";

?>