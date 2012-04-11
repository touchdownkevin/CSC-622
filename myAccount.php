<?php

//*** Fall 2011 Project -  sales systems
//*** my account page

session_start();
include("php/mysql.php");

if(!isset($_SESSION["login"]))
	echo "<script>locaion.href = 'default.php'</script>";

?>

<HTML>
	<HEAD>
		<LINK REL = "stylesheet" HREF = "css/general.css" TYPE = "text/css" MEDIA = "screen" />

		<SCRIPT TYPE = "text/javascript">

			function getSub(url) {

				var sub = document.getElementById("subID");

				sub.innerHTML = "<iframe src = '" + url + ".php' class = 'detail' />";
			}
		</SCRIPT>
	</HEAD>

	<BODY>
		<? include("header.php"); ?>
		<MS CLASS = "title"><? echo $_SESSION["ID"]."'s Account" ?></MS><BR />
		<A HREF = "#" ONCLICK = "getSub('profile'); ">Profile</A> 
		<A HREF = "#" ONCLICK = "getSub('purchases'); ">Purchases</A> 
		<A HREF = "#" ONCLICK = "getSub('sales'); ">Sales</A><BR />
		<DIV WIDTH = "100%" ID = "subID"><IFRAME SRC = "purchases.php" CLASS = "detail" /></DIV>
	</BODY>
</HTML>