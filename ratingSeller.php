<?php

//*** Fall 2011 Project -  sales systems
//*** rating page

session_start();
include("php/mysql.php");

$item_id = $_REQUEST["item_id"];
$from = $_REQUEST["from"];

$query1 = "select Buyer.email, Buyer.purchase_id, Items.name, Seller.email from Buyer, Purchases, Won, Items, Seller where Buyer.purchase_id = Purchases.purchase_id and
	Purchases.purchase_id = Won.purchase_id and Won.item_id = $item_id and Seller.purchase_id = Purchases.purchase_id";

$result1 = split("&;&", executeSQL4($query1));

//*** check for illegal access
if($_SESSION["ID"] != trim($result1[1]))
	echo"<script>location.href = './default.php'</script>";

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

			var submit_xhr;			//*** Ajax object submits form

			function trim(str) {

				return str.replace(/^\s+/, '').replace(/\s+$/,'');
			}

			//*** Ajax initializing *******************************************
			function initAjax() {

				submit_xhr = new XMLHttpRequest();

				submit_xhr.onreadystatechange = function() {

					if((submit_xhr.readyState == 4) && (submit_xhr.status == 200)) {

						var message = submit_xhr.responseText;

						if(trim(message) == "OK") {

							location.href = "./purchases.php";
						} else {
							alert(message);
						}
					}
				}
			}

			function jump(item_id) {

				location.href = "item.php?itemID=" + item_id;
			}

			function submitAjax() {

				var URL = "php/rating.php";

				var item_id = document.getElementById("item_id").value;
				var rate = document.getElementById("rating_id").value;
				var from = document.getElementById("from").value;
				var comment = document.getElementById("comment_id").value;
				var seller = document.getElementById("seller").value;

				var queryString = "item_id=" + item_id + "&rate=" + rate + "&from=" + from + "&comment=" + comment + 
					"&seller=" + seller + "&purchase_id=<? echo $result1[2] ?>&buyer=<? echo $result1[1] ?>";

				submit_xhr.open("POST", URL, true);

				submit_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				submit_xhr.send(queryString);
			}
		</SCRIPT>
	</HEAD>

	<BODY ALIGN = "center" ONLOAD = "initAjax(); ">
		<? include("header.php") ?>

		<MS CLASS = "title">Rate Seller</MS><BR />
		<TABLE ALIGN = "center">
		<TR><TD>Item Name: </TD><TD><A CLASS = 'item_link' HREF = '#' ONCLICK = 'jump(<? echo $item_id ?>); '><? echo $result1[3] ?></A></TD></TR>
		<TR><TD>Seller: </TD><TD><? echo $result1[4] ?></TD></TR>
		<TR><TD>Rating: </TD><TD><SPAN data-dojo-type = "dojox.form.Rating" data-dojo-props = "numStars:5">
			<SCRIPT TYPE = "dojo/event" data-dojo-event = "onChange">
				dojo.query('#rating_id')[0].value = this.value;
					dojo.query('#rating_id')[0].innerHTML = this.value;
			</SCRIPT>
		</SPAN></TD></TR>
		<TR><TD>Comment:</TD><TD><INPUT TYPE = "text" ID = "comment_id" SIZE = "50" /></TD></TR>
		<TR><TD></TD><TD><INPUT TYPE = "button" CLASS = "g-bt" VALUE = "Submit" ONCLICK = "submitAjax(); " /></TD></TR></TABLE>
		<INPUT TYPE = "text" ID = "rating_id" HIDDEN = "hidden" VALUE = "0" />
		<INPUT TYPE = "text" ID = "item_id" HIDDEN = "hidden" VALUE = "<? echo $item_id ?>" />
		<INPUT TYPE = "text" ID = "from" HIDDEN = "hidden" VALUE = "<? echo $from ?>" />
		<INPUT TYPE = "text" ID = "seller" HIDDEN = "hidden" VALUE = "<? echo $result1[4] ?>" />
	</BODY>
</HTML>