<?php

//*** Fall 2011 Project -  sales systems
//*** register page

session_start();

include("mysql.php");

//*** illegal access
if(!isset($_REQUEST["email"]))
	echo "<script>location.href = '../default.php'</script>";

$email = $_REQUEST["email"];
$pass = $_REQUEST["pass"];
$phone = $_REQUEST["phone"];
$cphone = $_REQUEST["cphone"];
$fname = $_REQUEST["fname"];
$mname = $_REQUEST["mname"];
$lname = $_REQUEST["lname"];
$addr1 = $_REQUEST["addr1"];
$addr2 = $_REQUEST["addr2"];
$city = $_REQUEST["city"];
$state = $_REQUEST["state"];
$zipcode = $_REQUEST["zipcode"];

$query0 = "insert into Accounts values ('$email', '$pass', '$phone', '$cphone', '$fname', '$mname', '$lname', 'active', 'user')";
executeSQL2($query0);

$query1 = "select add_id from Address where city = '$city' and state = '$state' and zipcode = '$zipcode'";
$result1 = executeSQL1($query1);

if($result1 == null) {
	$query2 = "select MAX(add_id) from Address";
	$result2 = executeSQL1($query2);
	$add_id = $result2 + 1;

	$query3 = "insert into Address values ($add_id, '$city', '$state', '$zipcode')";
	executeSQL2($query3);
} else 
	$add_id = $result1;

$query4 = "insert into AddressBooks values ('$email', '$add_id', 1, '$addr1', '$addr2')";
executeSQL2($query4);

$_SESSION["login"] = "login";
$_SESSION["ID"] = $email;
$_SESSION["name"] = $fname." ".$mname." ".$lname;

print("OK");

?>