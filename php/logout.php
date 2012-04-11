<?php

session_start();
include("mysql.php");

//*** Fall 2011 Project -  sales systems
//*** logout page

// illegal access
if(!isset($_SESSION["login"]))
	echo "<script>location.href = '../default.php'</script>";
else {
	session_destroy();

	print "<script>location.href = '../default.php'</script>";
}

?>