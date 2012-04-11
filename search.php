<?php

//*** Fall 2011 Project -  sales systems
//*** search page

session_start();
include("php/mysql.php");

if(!isset($_REQUEST["key"]))
	echo "<script>location.href = 'default.php'</script>";


function getKey() {

	echo $_REQUEST["key"];
}

function getResult() {

	$keyword = trim($_REQUEST["key"]);
	$cate = trim($_REQUEST["cate"]);
	$exclude = trim($_REQUEST["exclude"]);
	$bidding = trim($_REQUEST["bidding"]);
	$buynow = trim($_REQUEST["buynow"]);
	$pfrom = trim($_REQUEST["pfrom"]);
	$pto = trim($_REQUEST["pto"]);

	if(!isset($_REQUEST["exclude"])) {
		$query = "select Items.item_id, Items.name, Items.conditions, Items.pic, Posts.buynow, Posts.bidding, Posts.email, unix_timestamp(Posts.period) - unix_timestamp(NOW()) ";
		$query = $query."from Items, Posts, Categories where Items.item_id = Posts.item_id ";
		$query = $query." and Posts.status = 'active' and Items.name like '%$keyword%' and Items.cate_id = Categories.cate_id";
	} else {
		$query = "select Items.item_id, Items.name, Items.conditions, Items.pic, Posts.buynow, Posts.bidding, Posts.email, unix_timestamp(Posts.period) - unix_timestamp(NOW()) ";
		$query = $query."from Items, Posts, Categories ";
		$query = $query."where Items.item_id = Posts.item_id and Posts.status = 'active' and Items.name like '%$keyword%' ";
		$query = $query."and Items.cate_id = Categories.cate_id ";


		if(trim($exclude) != "")
			$query = $query."and Items.name not like '%$exclude%' ";

		if($bidding == "false")
			$query = $query."and Posts.bidding = 0 ";

		if($buynow == "false")
			$query = $query."and Posts.buynow = 0 ";
	}

	if(trim($cate) != "All Categories") {
		$query2 = "select cate_id from Categories where cate_name = '$cate'";
		$result2 = trim(executeSQL1($query2));

		$query = $query." and Categories.parent_id = $result2";
	}

	$items = split("&&;&&", executeSQL3($query));

	$result = (count($items) - 1)." result(s) for '$keyword'<BR />";

	if(count($items) > 1) {
		$result = $result."<TABLE><TR><TH WIDTH = '110'>Picture</TH><TH WIDTH = '350'>TITLE</TH><TH WIDTH = '200'>Seller</TH><TH WIDTH = '150'>Type</TH><TH WIDTH = '200'>Price</TH><TH WIDTH = '150'>Remains</TH>";

		for($i = 0; $i < count($items) - 1; $i++) {
			$details = split("&;&", $items[$i]);

			//*** check price rang
			$bFlag = checkPriceRange($details[1], $details[5], $details[6]);

			//*** check price range
			if($pfrom > 0) {
				$query3 = "select count(*) from Posts where item_id = $details[1] ";
				$query3 = $query3."and (buynow >= $pfrom or $pfrom <= (select max(price) from Bid where Bid.item_id = $details[1]))";
				$result3 = trim(executeSQL1($query3));

				if($result3 == 0)
					$bFlag = false;
			}

			if($pto > 0) {
				$query3 = "select count(*) from Posts where item_id = $details[1] ";
				$query3 = $query3."and (buynow <= $pto or $pto >= (select max(price) from Bid where Bid.item_id = $details[1]))";
				$result3 = trim(executeSQL1($query3));

				if($result3 == 0)
					$bFlag = false;
			}

			if($bFlag) {
				$tempResult = "<TR>";

				$tempResult = $tempResult."<TD><IMG SRC = './itemImage/$details[4]' WIDTH = '100' HEIGHT = '100' ONCLICK = 'resizeImg(this.src);' /></A></TD>";

				$tempResult = $tempResult."<TD><A HREF = '#' ONCLICK = 'jumpDetail($details[1]); '>$details[2]</A></TD>";
				$tempResult = $tempResult."<TD><A HREF = '#'>$details[7]</A>".getRate($details[7])."</TD>";
				$tempResult = $tempResult."<TD>".getTyp($details[5], $details[6])."</TD>";
				$tempResult = $tempResult."<TD>".getPrices($details[1], $details[5], $details[6])."</TD>";
				$tempResult = $tempResult."<TD>".getRemain($details[8])."</TD>";

				$tempResult = $tempResult."</TR>";

				$result = $result.$tempResult;
			}
		}

		$result = $result."</TABLE>";
	}

	echo $result;
}

function getTyp($buynow, $bidding) {

	$result = "";

	if($buynow != 0)
		$result = $result."BuyNow<BR />";

	if($bidding != 0)
		$result = $result."Bidding";

	return $result;
}

function getPrices($item_id, $buynow, $bidding) {

	$result = "";

	if($buynow != 0)
		$result = $result."\$$buynow (Buynow Price)<BR />";

	if($bidding != 0) {
		$query1 = "select max(price) from Bid where item_id = $item_id";
		$result1 = trim(executeSQL1($query1));

		if($result1 == "")
			$result = $result."\$$bidding (Current Bidding)";
		else
			$result = $result."\$$result1 (Current Bidding)";
	}

	return $result;		
}

function checkPriceRange($item_id, $buynow, $bidding) {

	$result = true;

	if($bidding != 0) {
		$query1 = "select max(price) from Bid where item_id = $item_id";
		$result1 = trim(executeSQL1($query1));

		if($result1 != "")
			$bidding = $result1;
	}

	return $result;
}

function getRemain($seconds) {

	$result = "";

	$days = (int)($seconds / 60 / 60 / 24);
	$remainDate = $seconds % (60 * 60 * 24);

	$hours = (int)($remainDate / 60 / 60);
	$remainDate = $remainDate % (60 * 60);

	$minutes = (int)($remainDate / 60);

	if($days > 0)
		$result = $result.$days."D ";
	if($hours > 0)
		$result = $result.$hours."H ";
	if($minutes > 0)
		$result = $result.$minutes."M";

	return $result;
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
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

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

			function trim(str) {

				return str.replace(/^\s+/, '').replace(/\s+$/,'');
			}

			function jumpDetail(itemID) {

				location.href = "item.php?itemID=" + itemID;
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
		<? include("header.php") ?>
		<DIV ID = "search_result" ALIGN = "center"><? getResult(); ?></DIV>
	</BODY>
</HTML>