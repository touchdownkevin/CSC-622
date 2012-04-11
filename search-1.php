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

			var search_xhr;				// *** Ajax object handles seaching
			var rate_xhr;					//*** Ajax object gets rate

			function trim(str) {

				return str.replace(/^\s+/, '').replace(/\s+$/,'');
			}

			function getType(buynow, bidding) {

				var result = "";

				if(buynow != 0)
					result += "BuyNow<BR />";
				if(bidding != 0)
					result += "Bidding";

				return result;
			}

			function getMax(first, second) {

				if(first > second)
					return first;
				else
					return second;
			}

			function getRemain(seconds) {

				var result = "";
				var currentDate = new Date();

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

				 return result;
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

			//*** Ajax initializing *************************************************
			function initAjax() {

				search_xhr = new XMLHttpRequest();	//*** initialize search
				rate_xhr = newXMLHttpRequest(); 		//*** initialize rate

				//*** search
				search_xhr.onreadystatechange = function() {

					if((search_xhr.readyState == 4) && (search_xhr.status == 200)) {

						var message = search_xhr.responseText;
						var items = message.split("&&;&&");
						var result = (items.length - 1) + " result(s) for '" + document.getElementById("searchID").value + "'.<BR />";

						if(items.length > 1) {
							result += "<TABLE><TR><TH WIDTH = '110'>Picture</TH><TH WIDTH = '400'>TITLE</TH><TH WIDTH = '200'>Seller</TH><TH WIDTH = '150'>Type</TH><TH WIDTH = '150'>Price</TH><TH WIDTH = '150'>Remains</TH>";

							for(i = 0; i < items.length - 1; i++) {
								var details = items[i].split("&;&");

								var tempResult = "<TR>";

								tempResult += "<TD><IMG SRC = './itemImage/" + details[4] + "' WIDTH = '100' HEIGHT = '100' ONCLICK = 'resizeImg(this.src);' /></A></TD>";

								tempResult += "<TD><A HREF = '#' ONCLICK = 'jumpDetail(" + details[1] + "); '>" + details[2] + "</A></TD>";
								tempResult += "<TD><A HREF = '#'>" + details[7] + getRate(details[7]) + "</A></TD>";
								tempResult += "<TD>" + getType(details[5], details[6]) + "</TD>";
								tempResult += "<TD>$" + getMax(details[5], details[6]) + "</TD>";
								tempResult += "<TD>" + getRemain(details[8]) + "</TD>";

								tempResult += "</TR>";

								result += tempResult;
							}

							result += "</TABLE>";
						}

						document.getElementById("search_result").innerHTML = result;
					}
				}
			}

			function searchAjax() {

				var URL = "php/search.php";
				var key = "<? getKey(); ?>";

				var queryString = "keyword=" + trim(document.getElementById("searchID").value)
					+ "&cate=" + trim(document.getElementById("categories").value);

				search_xhr.open("POST", URL, true);

				search_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				search_xhr.send(queryString);
			}

			function getRate(email) {

				var URL = "php/get_rate.php";

				var queryString = "email=" + email + "&from=seller";

				rate_xhr.open("POST", URL, true);

				rate_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				rate_xhr.send(queryString);
			}
		</SCRIPT>
	</HEAD>

	<BODY ALIGN = "center" ONLOAD = "initAjax(); searchAjax(); ">
		<? include("header.php") ?>
		<DIV ID = "search_result" ALIGN = "center"></DIV>
	</BODY>
</HTML>