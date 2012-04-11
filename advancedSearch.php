<?php

//*** Fall 2011 Project -  sales systems
//*** advanced search page

function getKey() {

	echo $_REQUEST["key"];
}

function getCate() {

	echo $_REQUEST["cate"];
}

?>

<HTML>
	<HEAD>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

		<LINK REL = "stylesheet" HREF = "css/general.css" TYPE = "text/css" MEDIA = "screen" />

		<SCRIPT TYPE = "text/javascript">

			function aSearch() {

				var key2 = document.getElementById("key2").value;
				var exclude = document.getElementById("exclude").value;
				var cate2 = document.getElementById("categories2").value;
				var pfrom = document.getElementById("pfrom").value;
				var pto = document.getElementById("pto").value;
				var bidding = document.getElementById("bidding").checked;
				var buynow = document.getElementById("buynow").checked;

				location.href = "search.php?key=" + key2 + "&cate=" + cate2 + "&exclude=" + exclude + "&pfrom=" + pfrom +
					"&pto=" + pto + "&bidding=" + bidding + "&buynow=" + buynow;
			}
		</SCRIPT>
	</HEAD>

	<BODY>
		<? include("header.php"); ?>

		<MS CLASS = "title">Advanced Search</MS><BR /><BR />

		<TABLE>
		<TR><TD>Keyword</TD><TD><INPUT TYPE = "text" ID = "key2" VALUE = "<? getKey(); ?>" /></TD></TR>
		<TR><TD>Exclude Words</TD><TD><INPUT TYPE = "text" ID = "exclude" /></TD></TR>
		<TR><TD>In This Category</TD><TD><SELECT ID = "categories2"><OPTION>All Categories<? getOptions(); ?></SELECT></TD></TR>
		<TR><TD>Price</TD><TD> From $ <INPUT TYPE = "text" ID = "pfrom" /> To $ <INPUT TYPE = "text" ID = "pto" /></TD></TR>
		<TR><TD>Buying Formats</TD><TD><INPUT TYPE = "checkbox" ID = "bidding" /> Auction<BR /><INPUT TYPE = "checkbox" ID = "buynow" /> BuyNow</TD></TR>
		<TABLE>

		<INPUT TYPE = "button" CLASS = "g-bt" VALUE = "Search" ONCLICK = "aSearch(); " />
	</BODY>
</HTML>