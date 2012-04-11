<?php

//*** Fall 2011 Project -  sales systems
//*** duplicated_ID page

include("mysql.php");

//*** illegal access
if(!isset($_REQUEST["email"]))
	echo "<script>location.href = '../default.php'</script>";

$email = $_REQUEST["email"];

$query = "select count(*) from Accounts where email = '$email'";
$result = executeSQL1($query);

if($result == 0)
	print("OK");
else
	print($result);

?>