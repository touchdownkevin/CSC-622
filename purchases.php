<?php

//*** Fall 2011 Project -  sales systems
//*** purchases page

session_start();

function getEnd() {

	$query1 = "select item_id, max(price) from Bid where email = '".$_SESSION["ID"]."' group by item_id";
	$result1 = split("&&;&&", executeSQL3($query1));
	$result = "";
	$count = 0;

	if(count($result1) > 1) {
		$result = $result."<TABLE ALIGN = 'center'><TR><TH WIDTH = '150'>Picture</TH><TH WIDTH = '250'>Title</TH><TH WIDTH = '150'>Seller</TH><TH WIDTH = '150'>Your Status</TH><TH WIDTH = '150'>Status of Item</TH><TH>Action</TH></TR>";

		for($i = 0; $i < count($result1) - 1; $i++) {
			$items = split("&;&", $result1[$i]);

			$query2 = "select max(price) from Bid where item_id = '$items[1]'";
			$result2 = executeSQL1($query2);

			$query3 = "select Items.pic, Items.name, Posts.email, Posts.status ";
			$query3 = $query3."from Items, Posts ";
			$query3 = $query3."where Items.item_id = Posts.item_id and Posts.item_id = $items[1]";

			$result3 = split("&;&", executeSQL4($query3));

			$query4 = "select Seller.status from Won, Seller where Seller.purchase_id = Won.purchase_id and Won.item_id = $items[1]";
			$rate_status = trim(executeSQL1($query4));

			if(trim($result3[4]) == 'end') {
				$result = $result."<TR CLASS = 'items'><TD CLASS = 'picture'><IMG SRC = 'itemImage/$result3[1]' width = '100' height = '100' ONCLICK = 'resizeImg(this.src);' /></A></TD>";
				$result = $result."<TD><A CLASS = 'item_link' HREF = '#' ONCLICK = 'jump($items[1]); '>$result3[2]</A></TD><TD>$result3[3]".getRate($result3[3])."</TD>";

				if($result2 == $items[2]) {
					$result = $result."<TD>Won</TD><TD>$result3[4]</TD>";

					if($rate_status == "wating")
						$result = $result."<TD><INPUT TYPE = 'button' CLASS = 'g-bt' VALUE = 'RATE' ONCLICK = 'jump2($items[1]); ' /></TD>";
				} else {
					$result = $result."<TD>Didn't Win</TD><TD>$result3[4]</TD>";
				}

				$result = $result."</TR>";

				$count++;
			} else if(trim($result3[4]) == 'canceled') {
				$result = $result."<TR CLASS = 'items'><TD CLASS = 'picture'><IMG SRC = 'itemImage/$result3[1]' width = '100' height = '100' ONCLICK = 'resizeImg(this.src);' /></A></TD>";
				$result = $result."<TD><A CLASS = 'item_link' HREF = '#' ONCLICK = 'jump($items[1]); '>$result3[2]</A></TD><TD>$result3[3]".getRate($result3[3])."</TD>";

				$result = $result."<TD>Didn't Win(canceled)</TD>";

				$result = $result."<TD>$result3[4]</TD></TR>";

				$count++;
			}
		}

		$result = $result."</TABLE>";
	} else {
		$result = $result."NONE";
	}

	if($count == 0)
		$result = "NONE";

	echo $result;
}

function getProcess() {

	$query1 = "select item_id, max(price) from Bid where email = '".$_SESSION["ID"]."' group by item_id";
	$result1 = split("&&;&&", executeSQL3($query1));
	$result = "";
	$count = 0;

	if(count($result1) > 1) {
		$result = $result."<TABLE ALIGN = 'center'><TR><TH WIDTH = '150'>Picture</TH><TH WIDTH = '250'>Title</TH><TH WIDTH = '150'>Seller</TH><TH WIDTH = '150'>Your Status</TH><TH WIDTH = '150'>Status of Item</TH></TR>";

		for($i = 0; $i < count($result1) - 1; $i++) {
			$items = split("&;&", $result1[$i]);

			$query2 = "select max(price) from Bid where item_id = '$items[1]'";
			$result2 = executeSQL1($query2);

			$query3 = "select Items.pic, Items.name, Posts.email, Posts.status ";
			$query3 = $query3."from Items, Posts ";
			$query3 = $query3."where Items.item_id = Posts.item_id and Posts.item_id = $items[1]";

			$result3 = split("&;&", executeSQL4($query3));

			if(trim($result3[4]) == 'active') {
				$result = $result."<TR CLASS = 'items'><TD CLASS = 'picture'><IMG SRC = 'itemImage/$result3[1]' width = '100' height = '100' ONCLICK = 'resizeImg(this.src);' /></A></TD>";
				$result = $result."<TD><A CLASS = 'item_link' HREF = '#' ONCLICK = 'jump($items[1]); '>$result3[2]</A></TD><TD>$result3[3]".getRate($result3[3])."</TD>";

				if($result2 == $items[2]) {
					$result = $result."<TD>Winning</TD>";
				} else {
					$result = $result."<TD>Not Winning</TD>";
				}

				$result = $result."<TD>$result3[4]</TD></TR>";

				$count++;
			}
		}

		$result = $result."</TABLE>";
	} else {
		$result = $result."NONE";
	}

	if($count == 0)
		$result = "NONE";

	echo $result;
}

function getRate($email) {

	$query1 = "select count(rating) from Seller where email = '$email' and status = 'rated'";
	$count = trim(executeSQL1($query1));

	$query2 = "select sum(rating) from Seller where email = '$email' and status = 'rated'";
	$sum = trim(executeSQL1($query2));

	if($count != 0) {
		$rate = ceil($sum / $count);

		return "<DIV data-dojo-type = 'dojox.form.Rating' data-dojo-props = 'numStars:5, value:$rate'></DIV>";
	} else
		return "";
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

			function jump(item_id) {

				location.href = "item.php?itemID=" + item_id;
			}

			function jump2(item_id) {

				location.href = "ratingSeller.php?item_id=" + item_id + "&from=buyer";
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
		</SCRIPT>
	</HEAD>

	<BODY ALIGN = "center">
		<? include("header.php"); ?>
		<MS CLASS = "title"><? echo $_SESSION["ID"]."'s Account" ?></MS><BR />

		<A HREF = "#" ONCLICK = "location.href='profile.php'">Profile</A> 
		<A HREF = "#" ONCLICK = "location.href='purchases.php'">Purchases</A> 
		<A HREF = "#" ONCLICK = "location.href='sales.php'">Sales</A><BR />

		<DIV CLASS = "my_account">
			Your Purchas History:
			<DIV ID = "won">
			Items That You Bidded
			<? getEnd(); ?>
			</DIV>

			<DIV ID = "processing">
			Items That Is Processing<BR />
			<? getProcess(); ?>
			</DIV>
		</DIV>
	</BODY>
</HTML>