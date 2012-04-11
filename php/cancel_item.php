<?php

//*** Fall 2011 Project -  sales systems
//*** cancel item

include("mysql.php");

if(!isset($_REQUEST["legal"]))
	echo "<script>location.href = '../default.php'</script>";

$item_id = $_REQUEST["item_id"];
$header  = "Content-type: text/html; charset=iso-8859-1";

$query1 = "update Posts set status = 'canceled' where item_id = $item_id";
executeSQL2($query1);

$query2 = "select distinct(email) from Bid where item_id = $item_id";
$result2 = executeSQL5($query2);

$query3 = "select name from Items where item_id = $item_id";
$result3 = trim(executeSQL1($query3));

$title = "Seller canceled the auction of the item, $result3";
$message = "Seller canceled the item, $result3, so the item is no longer available.<BR />Your bidding has been also canceled.";

$emails = split("&&;&&", $result2);

for($i = 1; $i < count($emails); $i++) {
	mail($emails[$i], $title, $message, $header);
}

print "OK";

?>