<?php

//*** Fall 2011 Project -  sales systems
//*** profile page

session_start();
include("php/mysql.php");

$phone = "";
$cphone = "";
$fname = "";
$mname = "";
$lname = "";

$query1 = "select phone, cphone, fname, mname, lname from Accounts where email = '".$_SESSION["ID"]."'";
$result1 = executeSQL4($query1);
$items = split("&;&", $result1);

$phone = $items[1];
$cphone = $items[2];
$fname = $items[3];
$mname = $items[4];
$lname = $items[5];

?>

<HTML>
	<HEAD>
		<LINK REL = "stylesheet" HREF = "css/general.css" TYPE = "text/css" MEDIA = "screen" />

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

							alert("Successfully Updated!");

							top.getSub('profile');
						}
					}
				}
			}

			function unlockAllInfo() {

				document.getElementById("phone").removeAttribute("READONLY");
				document.getElementById("cphone").removeAttribute("READONLY");
				document.getElementById("fname").removeAttribute("READONLY");
				document.getElementById("mname").removeAttribute("READONLY");
				document.getElementById("lname").removeAttribute("READONLY");

				document.getElementById("edit").setAttribute("HIDDEN", "hidden");

				document.getElementById("reset").removeAttribute("HIDDEN");
				document.getElementById("clear").removeAttribute("HIDDEN");
				document.getElementById("cancel").removeAttribute("HIDDEN");
				document.getElementById("submit").removeAttribute("HIDDEN");
			}

			function reset() {

				document.getElementById("phone").value = "<? echo $phone ?>";
				document.getElementById("cphone").value = "<? echo $cphone ?>";
				document.getElementById("fname").value = "<? echo $fname ?>";
				document.getElementById("mname").value = "<? echo $mname ?>";
				document.getElementById("lname").value = "<? echo $lname ?>";
			}

			function clearAll() {

				document.getElementById("phone").value = "";
				document.getElementById("cphone").value = "";
				document.getElementById("fname").value = "";
				document.getElementById("mname").value = "";
				document.getElementById("lname").value = "";
			}

			function cancel() {

				reset();

				document.getElementById("phone").setAttribute("READONLY", "readonly");
				document.getElementById("cphone").setAttribute("READONLY", "readonly");
				document.getElementById("fname").setAttribute("READONLY", "readonly");
				document.getElementById("mname").setAttribute("READONLY", "readonly");
				document.getElementById("lname").setAttribute("READONLY", "readonly");

				document.getElementById("edit").removeAttribute("HIDDEN");

				document.getElementById("cancel").setAttribute("HIDDEN", "hidden");
				document.getElementById("clear").setAttribute("HIDDEN", "hidden");
				document.getElementById("reset").setAttribute("HIDDEN", "hidden");
				document.getElementById("submit").setAttribute("HIDDEN", "hidden");	
			}

			function submitAjax() {

				var URL = "php/updateProfile.php";

				var phone = document.getElementById("phone").value;
				var cphone = document.getElementById("cphone").value;
				var fname = document.getElementById("fname").value;
				var mname = document.getElementById("mname").value;
				var lname = document.getElementById("lname").value;

				var queryString = "phone=" + phone + "&cphone=" + cphone + "&fname=" + fname + "&mname=" + 
						mname + "&lname=" + lname + "&legal=true";

				submit_xhr.open("POST", URL, true);

				submit_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				submit_xhr.send(queryString);
			}
		</SCRIPT>
	</HEAD>

	<BODY ALIGN = "center" ONLOAD = "initAjax(); ">
		<? include("header.php"); ?>
		<MS CLASS = "title"><? echo $_SESSION["ID"]."'s Account" ?></MS><BR />

		<A HREF = "#" ONCLICK = "location.href='profile.php'">Profile</A> 
		<A HREF = "#" ONCLICK = "location.href='purchases.php'">Purchases</A> 
		<A HREF = "#" ONCLICK = "location.href='sales.php'">Sales</A><BR />

		<TABLE ALIGN = "center">
			<TR><TD>email</TD><TD><INPUT TYPE = "text" ID = "email" VALUE = "<? echo $_SESSION['ID'] ?>" READONLY = "readonly" /></TD></TR>
			<TR><TD>Home Phone</TD><TD><INPUT TYPE = "text" ID = "phone" VALUE = "<? echo $phone ?>" READONLY = "readonly" /></TD></TR>
			<TR><TD>Cell Phone</TD><TD><INPUT TYPE = "text" ID = "cphone" VALUE = "<? echo $cphone ?>" READONLY = "readonly" /></TD></TR>
			<TR><TD>First Name</TD><TD><INPUT TYPE = "text" ID = "fname" VALUE = "<? echo $fname ?>" READONLY = "readonly" /></TD></TR>
			<TR><TD>Middle Name</TD><TD><INPUT TYPE = "text" ID = "mname" VALUE = "<? echo $mname ?>" READONLY = "readonly" /></TD></TR>
			<TR><TD>Last Name</TD><TD><INPUT TYPE = "text" ID = "lname" VALUE = "<? echo $lname ?>" READONLY = "readonly" /></TD></TR>
		</TABLE>

		<INPUT CLASS = "g-bt" TYPE = "button" ID = "edit" VALUE = "EDIT" ONCLICK = "unlockAllInfo(); " /> 
		<INPUT CLASS = "g-bt" TYPE = "button" ID = "reset" VALUE = "RESET" ONCLICK = "reset(); " HIDDEN = "hidden" /> 
		<INPUT CLASS = "g-bt" TYPE = "button" ID = "clear" VALUE = "CLEAR" ONCLICK = "clearAll(); " HIDDEN = "hidden" /> 
		<INPUT CLASS = "g-bt" TYPE = "button" ID = "cancel" VALUE = "CANCEL" ONCLICK = "cancel(); " HIDDEN = "hidden" /> 
		<INPUT CLASS = "g-bt" TYPE = "button" ID = "submit" VALUE = "SUBMIT" ONCLICK = "submitAjax(); " HIDDEN = "hidden" /> 
	</BODY>
</HTML>