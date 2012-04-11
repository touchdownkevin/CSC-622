<?php

//*** Fall 2011 Project -  sales systems
//*** item page

session_start();

$itemID = $_REQUEST["itemID"];
$item_name;
$item_conditions;
$item_amount;
$item_description;
$item_pic;
$item_cateID;
$post_buynow;
$post_bidding;
$post_period;
$post_email;
$max_bid;
$post_status;

function init() {

	global $itemID, $item_name, $item_conditions, $item_amount, $item_description, $item_pic, $post_buynow, $post_bidding, $post_period, $item_cateID, $post_email, $post_status;

	$query1 = "select Items.name, Items.conditions, Items.amount, Items.description, Items.pic, Posts.buynow, Posts.bidding, Posts.period, Items.cate_id, Posts.email, Posts.status ";
	$query1 = $query1."from Items, Posts where Items.item_id = $itemID and Posts.item_id = $itemID";

	$result1 = executeSQL4($query1);

	$details = split("&;&", $result1);

	$item_name = $details[1];
	$item_conditions = $details[2];
	$item_amount = $details[3];
	$item_description = $details[4];
	$item_pic = $details[5];
	$post_buynow = $details[6];
	$post_bidding = $details[7];
	$post_period = $details[8];
	$item_cateID = $details[9];
	$post_email = $details[10];
	$post_status = $details[11];
}

function getCommonDetail() {

	global $itemID, $item_name, $item_conditions, $item_amount, $item_description, $item_pic, $post_buynow, $post_bidding, $post_period, $item_cateID, $post_email;
	$result = "<TABLE><TR><TD SIZE = '300'>";
	$result = $result."<IMG SRC = 'itemImage/$item_pic' WIDTH = '270' HEIGHT = '270' />";
	$result = $result."</TD><TD SIZE = '400'>";
	$result = $result."Title: $item_name<BR />Seller: $post_email<BR />Item condition: $item_conditions<BR />Time left: ".getRemain()."<BR />";
	$result = $result.getPurchase()."</TD></TR></TABLE>";

	echo $result;
}

function getPurchase() {

	global $post_email, $itemID, $post_bidding, $post_buynow, $max_bid, $post_status;
	$result = "";

	if($_SESSION["ID"] == $post_email)
		return "This is your item";
	else {
		if(trim($post_status) == "active") {
			// for bidding
			if($post_bidding > 0) {
				$query1 = "select max(price) from Bid where item_id = $itemID";
				$result1 = executeSQL1($query1);

				if($result1 > $post_bidding) {
					$result = $result."Current Bid: \$$result1";
					$max_bid = $result1;
				} else {
					$result = $result."Current Bid: \$$post_bidding";
					$max_bid = $post_bidding;
				}

				$result = $result."<BR />Enter Your Price: \$<INPUT TYPE = 'text' ID = 'bidID' /><INPUT TYPE = 'button' VALUE = 'BID' ONCLICK = 'bidAjax(); '><BR />";
			}

			// for buynow
			if($post_buynow > 0){
				$result = $result."Price: \$$post_buynow";
				$result = $result."<INPUT TYPE = 'button' VALUE = 'BUYNOW' ONCLICK = 'buynowAjax(); ' /><BR />";
			}
		} else if(trim($post_status) == "end") {
			$result = $result."Auction Ended<BR />";
		}
	}

	return $result;
}

function getDetail() {

	global $itemID, $item_cateID;

	$titles = split(",", getTitles($item_cateID));

	$query1 = "select * from ".trim($titles[0])." where item_id = '$itemID'";
	$result1 = executeSQL4($query1);

	$details = split("&;&", $result1);

	$result = "<TABLE><TR>";

	for($i = 1; $i < count($titles); $i++) {
		$result = $result."<TD SIZE = '150'>".$titles[$i]."</TD><TD SIZE = '400'>";

		if(trim($details[$i + 1]) == "")
			$result = $result."N/A";
		else
			$result = $result.$details[$i + 1];

		$result = $result."</TD><TD SIZE = '1'></TD></TR><TR>";
	}

	$result = $result."</TR></TABLE>";

	echo $result;
}

function getTitles($cate_id) {

	$query1 = "select cate_name from Categories where cate_id = '$cate_id'";
	$result1 = executeSQL1($query1);

	$conn = connect();

	$query2 = "select * from $result1";
	$result2 = mysql_query($query2);

	$return = "";

	if($result2 != null) {
		$i = 1;

		while($i < mysql_num_fields($result2)) {
			$meta = mysql_fetch_field($result2, $i);

			$return = $return.$meta->name;

			$i++;

			if($i < mysql_num_fields($result2))
				$return = $return.",";
		}
	}

	mysql_free_result($result2);
	mysql_close($conn);

	return $result1.",".$return;
}

function getDescription() {

	global $item_description;

	echo $item_description;
}

function getRemain() {

	global $post_period;
	$return = "<div id = 'remainDiv'><input type = 'text' id = 'remainInput' value = '$post_period' hidden = 'hidden' /></div>";

	return $return;
}

function getHiddenValues() {

	global $itemID, $max_bid, $post_buynow;

	$result = "<INPUT TYPE = 'text' ID = 'loginID' VALUE = '".$_SESSION["ID"]."' HIDDEN = 'hidden' />";
	$result = $result."<INPUT TYPE = 'text' ID = 'itemID' VALUE = '$itemID' HIDDEN = 'hidden' />";
	$result = $result."<INPUT TYPE = 'text' ID = 'buynowPrice' VALUE = '$post_buynow' HIDDEN = 'hidden' />";
	$result = $result."<INPUT TYPE = 'text' ID = 'bidPrice' VALUE = '$max_bid' HIDDEN = 'hidden' />";

	echo $result;
}

function getHistory() {

	global $itemID, $post_email;

	if($_SESSION["ID"] == $post_email) {
		$result = "<TABLE><TR><TH>Bidder</TH><TH>Price</TH><TH>Time</TH></TR>";

		$query1 = "select email, price, bid_time from Bid where item_id = $itemID";
		$result1 = split("&&;&&", executeSQL3($query1));

		for($i = 0; $i < count($result1) - 1; $i++) {
			$items = split("&;&", $result1[$i]);

			$result = $result."<TR><TD>$items[1]</TD><TD>$items[2]</TD><TD>$items[3]</TD></TR>";
		}

		$result = $result."</TABLE>";

		echo $result;
	}
}

?>

<HTML>
	<HEAD>
		<LINK REL = "stylesheet" HREF = "css/general.css" TYPE = "text/css" MEDIA = "screen" />

		<SCRIPT TYPE = "text/javascript">

			var bid_xhr;				//*** Ajax object handles bid
			var buynow_xhr;				//*** Ajax object handles buynow

			function trim(str) {

				return str.replace(/^\s+/, '').replace(/\s+$/,'');
			}

			//*** Ajax initializing ****************************************************
			function initAjax() {

				bid_xhr = new XMLHttpRequest();
				buynow_xhr = new XMLHttpRequest();

				bid_xhr.onreadystatechange = function() {

					if((bid_xhr.readyState == 4) && (bid_xhr.status == 200)) {

						var message = bid_xhr.responseText;

						if(trim(message) != "OK")
							alert(message);
						else {
							alert("Bidding succeed!");

							location.reload();
						}
					}
				}

				buynow_xhr.onreadystatechange = function() {

					if((buynow_xhr.readyState == 4) && (buynow_xhr.status == 200)) {
					}
				}
			}

			function bidAjax() {


				var bid_price = parseFloat(trim(document.getElementById("bidID").value));
				var current_price = parseFloat(trim(document.getElementById("bidPrice").value));
				var item_id = trim(document.getElementById("itemID").value);
				var login_id = trim(document.getElementById("loginID").value);

				var URL = "php/bid.php";
				var queryString = "item_id=" + item_id + "&bid_price=" + bid_price + "&login_id=" + login_id;

				if(login_id == "") {
					alert("You've not logged in.");

					location.href = "login.php";
				} else {
					if(!(current_price + 1 <= bid_price))
						alert("Your bidding price should be more or equal to " + (current_price + 1));
					else {
						bid_xhr.open("POST", URL, true);

						bid_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						bid_xhr.send(queryString);
					}
				}
			}

			function buynowAjax() {

				if(trim(document.getElementById("loginID").value) == "") {
					alert("You've not logged in.");
					location.href = "login.php";
				} else {
				}
			}

			function init() {
				getRemain();
			}

			function getRemain() {

				var result = "";
				var currentDate = new Date();
				var seconds = document.getElementById("remainInput").value;

				var endDate = new Date(seconds);
				var remainDate = endDate - currentDate;
				remainDate = Math.floor(remainDate / 1000);

				var days = Math.floor(remainDate / 60 / 60 / 24);

				remainDate = remainDate % (60 * 60 * 24);

				var hours = Math.floor(remainDate / (60 * 60));
				remainDate = remainDate % (60 * 60);

				var minutes = Math.floor(remainDate / 60);

				if(days > 0)
					result += days + "D ";
				if(hours > 0)
					result += hours + "H ";
				if(minutes > 0)
					result += minutes + "M";

				document.getElementById("remainDiv").innerHTML = result;
			}
		</SCRIPT>
	</HEAD>

	<BODY ALIGN = "center" ONLOAD = "init(); initAjax(); ">
		<? include("header.php"); ?>
		<DIV ID = "common" ALIGN = "center"><? init(); getCommonDetail(); ?></DIV>
		<DIV ID = "history" ALIGN = "center"><? getHistory(); ?></DIV>
		<DIV ID = "detail" ALIGN = "center">Details: <BR /><? getDetail(); ?></DIV>
		<DIV ID = "description" ALIGN = "center">Description:<BR /><? getDescription(); ?></DIV>
		<DIV ID = "hiddenValues"><? getHiddenValues(); ?></DIV>
	</BODY>
</HTML>
