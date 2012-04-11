<?php

//*** Fall 2011 Project -  sales systems
//*** update profile page

session_start();
include("mysql.php");

if(!isset($_REQUEST["legal"]))
	echo "<script>location.href = '../default.php'</script>";

$email = $_SESSION["ID"];
$phone = $_REQUEST["phone"];
$cphone = $_REQUEST["cphone"];
$fname = $_REQUEST["fname"];
$mname = $_REQUEST["mname"];
$lname = $_REQUEST["lname"];

$query1 = "update Accounts set phone = '$phone', cphone = '$cphone', fname = '$fname', mname = '$mname', lname = '$lname' where email = '$email'";
executeSQL2($query1);

print "OK";

?>