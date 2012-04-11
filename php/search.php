<?php

//*** Fall 2011 Project -  sales systems
//*** search item

include("mysql.php");

//*** illegal access
if(!isset($_POST["keyword"]))
	echo "<script>location.href = '../default.php'</script>";

$keyword = $_REQUEST["keyword"];
$cate = $_REQUEST["cate"];

$query = "select Items.item_id, Items.name, Items.conditions, Items.pic, Posts.buynow, Posts.bidding, Posts.email, Posts.period ";
$query = $query."from Items, Posts, Categories where Items.item_id = Posts.item_id ";
$query = $query." and Posts.status = 'active' and Items.name like '%$keyword%' and Items.cate_id = Categories.cate_id";

if(trim($cate) != "All Categories") {
	$query2 = "select cate_id from Categories where cate_name = '$cate'";
	$result2 = trim(executeSQL1($query2));

	$query = $query." and Categories.parent_id = $result2";
}

$result = executeSQL3($query);

print $result

?>