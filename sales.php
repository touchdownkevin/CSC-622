<?php

//*** Fall 2011 Project -  sales systems
//*** sales page

session_start();

if(!isset($_SESSION["login"]))
	echo "<script>location.href = 'default.php'</script>";

function getDetail() {

	$query1 = "select Posts.period, Posts.status, Items.item_id, Items.name, Items.pic, period ";
	$query1 = $query1."from Posts, Items ";
	$query1 = $query1."where Posts.item_id = Items.item_id and Posts.email = '".$_SESSION["ID"]."' ";
	$query1 = $query1."order by period";

	$result1 = split("&&;&&", executeSQL3($query1));

	$result = "";

	if(count($result1) > 1) {

		$result = $result."<TABLE ALIGN = 'center'><TR><TH WIDTH = '150'>Picture</TH><TH WIDTH = '250'>Title</TH><TH WIDTH = '100'>";
		$result = $result."End Date</TH><TH WIDTH = '80'>Status</TH><TH>Winner (Price)</TH><TH>Action</TH></TR>";

		for($i = 0; $i < count($result1) - 1; $i++) {
			$items = split("&;&", $result1[$i]);

			$query2 = "select email, price from Bid where item_id = $items[3] and price = (select max(price) from Bid where item_id = $items[3])";
			$result2 = split("&;&", executeSQL4($query2));

			$result = $result."<TR CLASS = 'items'><TD CLASS = 'picture'><IMG SRC = 'itemImage/$items[5]' width = '100' height = '100' ONCLICK = 'resizeImg(this.src);' /></A></TD>";
			$result = $result."<TD><A CLASS = 'item_link' HREF = '#' ONCLICK = 'jump($items[3]); '>$items[4]</A></TD><TD>$items[6]</TD><TD>$items[2]</TD>";

			if($result2[1] != null && $items[2] == "end")
				$result = $result."<TD><A HREF = '#'>$result2[1]</A>".getRate($result2[1])."<BR/>(\$$result2[2])</TD>";
			else
				$result = $result."<TD>N/A</TD>";

			if($items[2] == "active")
				$result = $result."<TD><INPUT TYPE = 'button' CLASS = 'g-bt' VALUE = 'Cancel' ONCLICK = 'cancelAjax($items[3]);' />";
			else
				$result = $result."<TD>";

			$query3 = "select Buyer.status from Won, Buyer where Buyer.purchase_id = Won.purchase_id and Won.item_id = $items[3]";
			$rate_status = trim(executeSQL1($query3));

			if($rate_status == "wating")
				$result = $result."<INPUT TYPE = 'button' CLASS = 'g-bt' VALUE = 'RATE' ONCLICK = 'jump2($items[3]); ' /></TD>";
			else
				$result = $result."</TD>";

			$result = $result."</TR>";
		}

		$result = $result."</TABLE>";
	} else {
		$result = $result."NONE";
	}

	echo $result;
}

function getRate($email) {

	$query1 = "select count(rating) from Buyer where email = '$email' and status = 'rated'";
	$count = trim(executeSQL1($query1));

	$query2 = "select sum(rating) from Buyer where email = '$email' and status = 'rated'";
	$sum = trim(executeSQL1($query2));

	if($count != 0) {
		$rate = ceil($sum / $count);

		return "<DIV data-dojo-type = 'dojox.form.Rating' data-dojo-props = 'numStars:5, value:$rate'></DIV>";
	} else
		return $count;
}

?>

<HTML>
	<HEAD>
		<LINK REL = "stylesheet" HREF = "css/general.css" TYPE = "text/css" MEDIA = "screen" />
		<link rel="stylesheet" href="dojo/dojox/form/resources/Rating.css" />

		<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6/dojo/dojo.xd.js"
			djConfig="parseOnLoad: true">
		</script>

		<script>
			dojo.require("dojox.form.Rating");
			dojo.require("dojo.parser");
		</script>

		<SCRIPT TYPE = "text/javascript">

			var cancel_xhr;				//*** Ajax object handles cancel the posting

			function jump(item_id) {

				top.location.href = "item.php?itemID=" + item_id;
			}

			function jump2(item_id) {

				location.href = "ratingBuyer.php?item_id=" + item_id + "&from=seller";
			}

			function trim(str) {

				return str.replace(/^\s+/, '').replace(/\s+$/,'');
			}

			function resizeImg(osrc) {
 
				var bdiv =document.createElement('DIV'); 

				/*document.body.appendChild(bdiv); 

				bdiv.setAttribute("id", "bdiv"); 
				bdiv.style.position = 'absolute'; 
				bdiv.style.top = 0; 
				bdiv.style.left = 0; 
				bdiv.style.zIndex = 0; 
				bdiv.style.width = document.body.scrollWidth / 2; 
				bdiv.style.height = document.body.scrollHeight / 2; 
				bdiv.style.background = 'pink'; 
				bdiv.style.filter = "alpha(opacity=50)"; */

				var odiv = document.createElement('DIV'); 
				document.body.appendChild(odiv); 
				odiv.style.zIndex = 1; 
				odiv.setAttribute("id", "odiv"); 
				odiv.innerHTML = "<a href='javascript:void(closeImg())'><img id='oimg' src='"+osrc+"' width = 800 height = 400 border='0' /></a>"; 

				var img = document.all['oimg']; 
				var owidth = (document.body.clientWidth)/2 - (img.width)/2; 

				var oheight = (document.body.clientHeight)/2 - (img.height)/2; 
				odiv.style.position = 'absolute'; 
				odiv.style.top = oheight + document.body.scrollTop; 
				odiv.style.left = owidth; 

				scrollImg(); 
			} 

			function scrollImg() { 

				var odiv = document.all['odiv']; 
				var img = document.all['oimg']; 
				var oheight = (document.body.clientHeight)/2 - (img.height)/2 + document.body.scrollTop; 
				odiv.style.top = oheight; 
				settime = setTimeout(scrollImg, 100); 
			}
 
			function closeImg() { 

				document.body.removeChild(odiv); 
				//document.body.removeChild(bdiv); 

				clearTimeout(settime); 
			}

			//*** Ajax initializing *************************************************
			function initAjax() {

				cancel_xhr = new XMLHttpRequest();	//*** initialize cancel

				cancel_xhr.onreadystatechange = function() {

					if((cancel_xhr.readyState == 4) && (cancel_xhr.status == 200)) {

						var message = cancel_xhr.responseText;

						if(trim(message) == "OK") {
							location.reload();
						} else
							alert("Error occured: " + message);
					}
				}
			}

			function cancelAjax(item_id) {

				if(confirm("Are you sure to cancel this posting?")) {
					var URL = "php/cancel_item.php";

					var queryString = "legal=true&item_id=" + item_id;

					cancel_xhr.open("POST", URL, true);

					cancel_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					cancel_xhr.send(queryString);
				}
			}
		</SCRIPT>
	</HEAD>

	<BODY ALIGN = "center" ONLOAD = "initAjax();">
		<? include("header.php"); ?>
		<MS CLASS = "title"><? echo $_SESSION["ID"]."'s Account" ?></MS><BR />

		<A HREF = "#" ONCLICK = "location.href='profile.php'">Profile</A> 
		<A HREF = "#" ONCLICK = "location.href='purchases.php'">Purchases</A> 
		<A HREF = "#" ONCLICK = "location.href='sales.php'">Sales</A><BR />

		Your Posted Item(s): <BR />
		<? getDetail(); ?>
	</BODY>
</HTML>