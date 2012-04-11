<?php

//*** Fall 2011 Project -  sales systems
//*** get sub categories

session_start();

//*** illegal access
if(!isset($_REQUEST["parent_id"]))
	echo"<script>location.href = '../default.php'</script>";

include("mysql.php");

$parent_id = $_REQUEST["parent_id"];

$query = "select cate_id, cate_name from Categories where parent_id = $parent_id order by cate_name";
$result = executeSQL3($query);

print $result;

?>