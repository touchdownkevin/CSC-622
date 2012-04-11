<?php

//*** Fall 2011 Project -  sales systems
//*** header page

if(!defined("MYSQL_DEFINE"))
	include("php/mysql.php");

function loginStatus() {

	if($_SESSION['login'] == "login")
		echo "<DIV CLASS = 'wrapper-sublink'><A CLASS = 'sublink' HREF = '#' ONCLICK = 'location.href="."\"php/logout.php\""." '> Logout</A></DIV>
			<DIV CLASS = 'wrapper-sublink'><A CLASS = 'sublink' HREF = 'posting_item.php'>Posting Item</A></DIV>
			<DIV CLASS = 'welcome-user'>Welcome ".$_SESSION['name'].".</DIV> <DIV CLASS = 'wrapper-sublink'><A CLASS = 'sublink' HREF = 'purchases.php'> My Account</A></DIV>
			<DIV CLASS = 'wrapper-sublink'><A CLASS = 'sublink' HREF = '#' ONCLICK = 'location.href="."\"default.php\""." '>Home</A></DIV>";
	else
		echo "<DIV CLASS = 'welcome-user'>Hello, Guest!</DIV>
			<DIV CLASS = 'wrapper-sublink'><A CLASS = 'sublink' HREF = 'login.php'>Login</A></DIV> <DIV CLASS = 'wrapper-sublink'><A CLASS = 'sublink' HREF = 'register.php'>Register</A></DIV> 
			<DIV CLASS = 'wrapper-sublink'><A CLASS = 'sublink' HREF = '#' ONCLICK = 'location.href="."\"default.php\""." '>Home</A></DIV>";

	echo "<BR>";
}

function getOptions() {

	$query1 = "select cate_name from Categories where parent_id = 1 order by cate_name";
	$result1 = executeSQL5($query1);

	$items = split("&&;&&", $result1);

	for($i = 0; $i < count($items) - 1; $i++) {
		if($items[$i] == $_REQUEST["cate"])
			echo "<OPTION SELECTED>$items[$i]";
		else
			echo "<OPTION>$items[$i]";
	}
}

?>
		<DIV CLASS = "header">
			<DIV CLASS = "main-menu-content" ALIGN = "center"><? loginStatus(); ?></DIV>

			<DIV CLASS = "search-bar">
				<TABLE><TR>
				<TD><DIV CLASS = "header-logo">
					Logo Will Be HERE!
				</DIV></TD>

				<TD>Search </TD><TD WIDTH = "200"><SELECT ID = "categories"><OPTION>All Categories<? getOptions(); ?></SELECT></TD>
				<TD CLASS = "search_box"><INPUT TYPE = "text" CLASS = "search_box" ID = "searchID" VALUE = "<? echo $_REQUEST['key'] ?>" ONKEYPRESS = "if(event.keyCode==13) {location.href = 'search.php?key=' + document.getElementById('searchID').value + '&cate=' + document.getElementById('categories').value }" /></TD>
				<TD><INPUT TYPE = "button" CLASS = "s-bt" VALUE = "Search" ONCLICK = "location.href = 'search.php?key=' + document.getElementById('searchID').value + '&cate=' + document.getElementById('categories').value " /></TD>
				<TD><A HREF = "#" ONCLICK = "location.href = 'advancedSearch.php?key=' + document.getElementById('searchID').value + '&cate=' + document.getElementById('categories').value ">Advanced</A></TD>
				</TD></TABLE>
			</DIV>
		</DIV>