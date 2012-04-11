<?php

//*** Fall 2011 Project -  sales systems
//*** posting items

session_start();

if($_SESSION['login'] != "login") {
	echo "<SCRIPT>alert('You need to login first'); ";
	echo "location.href = 'default.php'</SCRIPT>";
}

function get_first_category() {

	$query = "select cate_id, cate_name from Categories where parent_id = 0 order by cate_name";

	$result = executeSQL3($query);

	$options = split("&&;&&", $result);

	for($i = 0; $i < count($options) - 1; $i++) {
		$item = split("&;&", $options[$i]);

		echo "<OPTION VALUE = '".$item[1]."'>".$item[2]."</OPTION>";
	}
}

?>

<HTML>
	<HEAD>
		<LINK REL = "stylesheet" HREF = "css/general.css" TYPE = "text/css" MEDIA = "screen" />

		<SCRIPT TYPE = "text/javascript">

			var cate2_xhr;		//*** Ajax object determinds second categories
			var cate3_xhr;		//*** Ajax object determinds third categories
			var next_xhr;			//*** Ajax object determaids detail description of item
			var bComplete;		//*** boolean for complete to choose the final category
			var post_xhr;			//*** Ajax object posts the item

			function trim(str) {

				return str.replace(/^\s+/, '').replace(/\s+$/,'');
			}

			function validateForm() {

				if(!bComplete) {
					alert("Please check the third category.");

					return false;
				} else {
					var name = trim(document.getElementById("name").value);
					var condition = trim(document.getElementById("condition").value);
					var amount = trim(document.getElementById("amount").value);
					var description = trim(document.getElementById("description").value);
					var days = trim(document.getElementById("days").value);
					var hrs = document.getElementById("hrs").value;
					var mins = document.getElementById("mins").value;

					var buynow = trim(document.getElementById("buynow").value);
					var bidding = trim(document.getElementById("bidding").value);
			
					if(name == "" || condition == "" || amount == "" || description == "" || (days == "" && hrs == "" && mins == "")) {
						alert("Fill out subject, condition, amount, period, and description.");

						return false;
					} else if(buynow == "" && bidding == "") {
						alert("You need to input either BuyNow or Bidding, or both.");

						return false;
					} else {
						return true;
					}
				}
			}

			function post() {

				if(validateForm()) {
					var option = document.getElementById("tcate");

					document.getElementById("sub_cate").value = 
						option.options[option.selectedIndex].value;

					postItem();
				}
			}

			function postItem() {

				document.getElementById("postingItem").submit();
			}

			//*** Ajax initializing *********************************************************************************
			function initAjax() {

				cate2_xhr = new XMLHttpRequest();		//*** initialize cate2
				cate3_xhr = new XMLHttpRequest();		//*** initialize cate3
				next_xhr = new XMLHttpRequest();		//*** initialize next
				post_xhr = new XMLHttpRequest();		//*** initialize post
				bComplete = false;				//*** initialize bComplete

				//*** cate 2 handler begins here *******************
				cate2_xhr.onreadystatechange = function() {

					if((cate2_xhr.readyState == 4) && (cate2_xhr.status == 200)) {

						var message = cate2_xhr.responseText;
						var options = message.split("&&;&&");
						var result = "";

						var select_box = document.getElementById("scate");

						for(i = 0; i < options.length - 1; i++) {
							var item = options[i].split("&;&");

							result += "<OPTION VALUE = '" + item[1] + "'>" + item[2] + "</OPTION>";
						}

						document.getElementById('scate').innerHTML = result;
						document.getElementById("tcate").innerHTML = "";
						document.getElementById("detail_input").innerHTML = "";

						bComplete = false;
					}
				}

				//*** cate 3 handler begins here *******************
				cate3_xhr.onreadystatechange = function() {

					if((cate3_xhr.readyState == 4) && (cate3_xhr.status == 200)) {

						var message = cate3_xhr.responseText;
						var options = message.split("&&;&&");
						var result = "";

						for(i = 0; i < options.length - 1; i++) {
							var item = options[i].split("&;&");

							result += "<OPTION VALUE = '" + item[1] + "'>" + item[2] + "</OPTION>";
						}

						document.getElementById('tcate').innerHTML = result;
						document.getElementById("detail_input").innerHTML = "";

						bComplete = false;
					}
				}

				//*** next handler begins here *******************
				next_xhr.onreadystatechange = function() {

					if((next_xhr.readyState == 4) && (next_xhr.status == 200)) {

						var message = next_xhr.responseText;
						var fields = trim(message).split(",");
						document.getElementById("temp").value = message;

						var result = " ";

						if(trim(message) != "") {
							for(i = 0; i < fields.length; i++) {
								result += fields[i] + ": <INPUT TYPE = 'text' ID = '" + fields[i] + "' NAME = '" + fields[i] + "' SIZE = '50' /><BR />";
							}

							document.getElementById("detail_input").innerHTML = result; 

							bComplete = true;
						} else {
							document.getElementById("detail_input").innerHTML = "";

							bComplete = false;
						}
					}
				}

				//*** post handler begins here *******************
				post_xhr.onreadystatechange = function() {

					if((post_xhr.readyState == 4) && (post_xhr.status == 200)) {

						var message = post_xhr.responseText;

						if(trim(message) == "OK") {
							location.href = "post.php";
						} else
							alert(message);
					}
				}
			}

			//*** Ajax caller to check the second categories
			function get_second_categoryAjax() {

				var URL = "php/get_subcategories.php";

				var option = document.getElementById("fcate");
				var queryString = "parent_id=" + option.options[option.selectedIndex].value;

				cate2_xhr.open("POST", URL, true);

				cate2_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				cate2_xhr.send(queryString);
			}

			//*** Ajax caller to check the third categories
			function get_third_categoryAjax() {

				var URL = "php/get_subcategories.php";

				var option = document.getElementById("scate");
				var queryString = "parent_id=" + option.options[option.selectedIndex].value;

				cate3_xhr.open("POST", URL, true);

				cate3_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				cate3_xhr.send(queryString);
			}

			//*** Ajax caller to show detailed description of item
			function nextAjax() {

				var URL = "php/get_detailed_description.php";

				var option = document.getElementById("tcate");
				var queryString = "cate_id=" + option.options[option.selectedIndex].value;

				next_xhr.open("POST", URL, true);

				next_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				next_xhr.send(queryString);
			}

			//*** Ajax caller to post the item
			function postAjax() {

				var URL = "php/post.php";

				var option = document.getElementById("tcate");
				var fields = document.getElementById("temp").value.split(",");

				var name = document.getElementById("name").value;
				var condition = document.getElementById("condition").value;
				var amount = document.getElementById("amount").value;
				var buynow = document.getElementById("buynow").value;
				var bidding = document.getElementById("bidding").value;
				var days = document.getElementById("days").value;
				var hrs = document.getElementById("hrs").value;
				var mins = document.getElementById("mins").value;
				var description = document.getElementById("description").value;
				var detail = trim(document.getElementById("temp").value);

				var queryString = "cate_id=" + option.options[option.selectedIndex].value + "&name=" + name + "&condition=" + condition +
					"&amount=" + amount + "&buynow=" + buynow + "&bidding=" + bidding + "&days=" + days + "&description=" + description +
					"&hrs=" + hrs + "&mins=" + mins + "&detail=" + detail;

				for(i = 0; i < fields.length; i++) {
					queryString = queryString + "&" + fields[i] + "=" + document.getElementById(fields[i]).value;
				}

				post_xhr.open("POST", URL, true);

				post_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				post_xhr.send(queryString);
			}
		</SCRIPT>
	</HEAD>

	<BODY ONLOAD = "initAjax();">
		<? include("header.php"); ?>
		Choose Category<BR />
		<SELECT NAME = "first_category" ID = "fcate" SIZE = "10" STYLE = "width: 200px" ONCHANGE = "get_second_categoryAjax();"><? get_first_category(); ?></SELECT>
		---
		<SELECT NAME = "second_category" ID = "scate" SIZE = "10" STYLE = "width: 200px" ONCHANGE = "get_third_categoryAjax();"></SELECT>
		---
		<SELECT NAME = "third_category" ID = "tcate" SIZE = "10" STYLE = "width: 200px" ONCHANGE = "nextAjax();"></SELECT>

		<FORM ACTION = "post.php" ID = "postingItem" enctype = "mutipart/form-data" method = "post">
			<DIV ID = "general_input">
				Subject*: <INPUT TYPE = "text" ID = "name" NAME = "name" SIZE = "50" /><BR />
				Condition*: <INPUT TYPE = "text" ID = "condition" NAME = "condition" SIZE = "50" /><BR />
				Quantity*: <INPUT TYPE = "text" ID = "amount" NAME = "amount" SIZE = "50" /><BR />
				BuyNow Price: <INPUT TYPE = "text" ID = "buynow" NAME = "buynow" SIZE = "50" /> (leave blank if you don't want this option.)<BR />
				Starting Bidding Price: <INPUT TYPE = "text" ID = "bidding" NAME = "bidding" SIZE = "50" /> (leave black if you don't want this option.)<BR />
				Posting Period*: <INPUT TYPE = "text" ID = "days" NAME = "days" SIZE = "10" /> days <INPUT TYPE = "text" ID = "hrs" NAME = "hrs" SIZE = "10" /> hours 
						<INPUT TYPE = "text" ID = "mins" NAME = "mins" SIZE = "10" /> minutes <BR />
				Picture: <INPUT CLASS = "g-bt" TYPE = "file" NAME = "itemImage" VALUE = "UPLOAD" /> Max 1MB. JPG and GIF ONLY!<BR />
				Description*: <TEXTAREA ID = "description" NAME = "description" COLS = "100" ROWS = "7"></TEXTAREA>
			</DIV>

			<DIV ID = "detail_input"></DIV>
			<DIV ID = "posting"><INPUT CLASS = "g-bt" VALUE = "Post" TYPE = "button" ONCLICK = "post(); " /></DIV>
			<INPUT TYPE = "text" ID = "temp" NAME = "detail" HIDDEN = "hidden" />
			<INPUT TYPE = "text" ID = "sub_cate" NAME = "sub_cate" HIDDEN = "hidden" />
		</FORM>
	</BODY>
</HTML>