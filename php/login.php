<?php

//*** Fall 2011 Project -  sales systems
//*** login controller

session_start();

//*** illegal access
if(!isset($_REQUEST["email"]))
	echo"<script>location.href = '../default.php'</script>";

include("mysql.php");

$email = $_REQUEST["email"];
$pass = $_REQUEST["pass"];

$query1 = "select count(*) from Accounts where email = '$email'";
$result1 = executeSQL1($query1);

if($result1 == 0)
	print("NOUSER");
else {
	$query2 = "select fname, mname, lname from Accounts where email = '$email' and password = '$pass'";
	$result2 = executeSQL1($query2);

	if($result2 != "") {
		$_SESSION['login'] = "login";
		$_SESSION['ID'] = $email;
		$_SESSION['name'] = $result2;

		echo "<script>history.back()</script>";
	} else
		print("WRONGPASS");
}

?>
