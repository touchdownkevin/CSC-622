<?php

//*** Fall 2011 Project -  sales systems
//*** post item

session_start();
include("mysql.php");

//*** illegal access
if(!isset($_REQUEST["cate_id"]))
	echo"<script>location.href = '../default.php'</script>";

$cate_id = $_REQUEST["cate_id"];
$name = $_REQUEST["name"];
$condition = $_REQUEST["condition"];
$amount = $_REQUEST["amount"];
$buynow = $_REQUEST["buynow"];
$bidding = $_REQUEST["bidding"];
$days = $_REQUEST["days"];
$hrs = $_REQUEST["hrs"];
$mins = $_REQUEST["mins"];
$description = $_REQUEST["description"];
$details = split(",", $_REQUEST["detail"]);

$item_id = 0;
$newPeriod = date("Y-m-d H:i:s", time() + ($days * 24 * 60 * 60) + ($hrs * 60 * 60) + ($mins * 60));

if($buynow == "")
	$buynow = 0;
if($bidding == "")
	$bidding = 0;

$query1 = "select max(item_id) from Items";
$result1 = executeSQL1($query1);

if($result1 != null)
	$item_id = $result1 + 1;

$query2 = "insert into Items values ($item_id, $cate_id, '$name', '$condition', $amount, '$description', 'default.jpg')";
executeSQL2($query2);

$query3 = "select cate_name from Categories where cate_id = $cate_id";
$result3 = executeSQL1($query3);

$query4 = "insert into $result3 (item_id";

for($i = 0; $i < count($details); $i++) {
	$query4 = $query4.", ".$details[$i];
}

$query4 = $query4.") values ($item_id";

for($i = 0; $i < count($details); $i++) {
	$query4 = $query4.", '". $_REQUEST[$details[$i]]."'";
}

$query4 = $query4.")";
executeSQL2($query4);

$query5 = "insert into Posts values ('".$_SESSION['ID']."', $item_id, $buynow, $bidding, '$newPeriod', 'active')";
executeSQL2($query5);

exec("./process.php $item_id > /dev/null &");

print "OK";

?>