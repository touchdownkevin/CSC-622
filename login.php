<?php

//*** Fall 2011 Project -  sales systems
//*** login page

session_start();

if($_SESSION['login'] == "login") {
	echo "<SCRIPT>alert('Invalid Access!!'); ";
	echo "location.href = 'default.php'</SCRIPT>";
}

?>

<HTML>
	<HEAD>
		<LINK REL = "stylesheet" HREF = "css/general.css" TYPE = "text/css" MEDIA = "screen" />

		<SCRIPT TYPE = "text/javascript">
			var login_xhr;		//*** Ajax object handles login

			function trim(str) {

				return str.replace(/^\s+/, '').replace(/\s+$/,'');
			}

			function loginValidation() {

				var bFlag = true;		//*** result
				var emailExpr = /^\w{3,20}(\.\w+){0,3}@\w+(\.\w+)+$/i // email validation
				var passExpr = /^\w{5,}$/i // password validation

				var loginEmail = document.getElementById("email").value;
				var loginPass = document.getElementById("pass").value;

				if(trim(loginEmail) == "") {
					bFlag = false;

					document.getElementById("emAlert").innerHTML = "* required to login";
				} else if(trim(loginEmail).search(emailExpr) == -1) {
					bFlag = false;

					document.getElementById("emAlert").innerHTML = "*not valid email address";
				} else
					document.getElementById("emAlert").innerHTML = "";

				if(trim(loginPass) == "") {
					bFlag = false;

					document.getElementById("pwAlert").innerHTML = "* required to login";
				} else if(trim(loginPass).search(passExpr) == -1) {
					bFlag = false;

					document.getElementById("pwAlert").innerHTML = "* at least 5 digits";
				} else
					document.getElementById("pwAlert").innerHTML = "";

				return bFlag;
			}

			function keypress(key) {

				if(key == 13)
					loginAjax();
			}

			//*** Ajax initializing *************************************************
			function initAjax() {

				login_xhr = new XMLHttpRequest();	//*** initialize login

				login_xhr.onreadystatechange = function() {

					if((login_xhr.readyState == 4) && (login_xhr.status == 200)) {

						var message = login_xhr.responseText;

						if(trim(message) == "WRONGPASS") {
							alert("go to wrong pass page");
						} else if(trim(message) == "NOUSER") {
							alert("go to no user page");
						} else
							document.write(message);
					}
				}
			}

			function loginAjax() {

				var URL = "php/login.php";

				var email = document.getElementById("email").value;
				var pass = document.getElementById("pass").value;

				var queryString = "email=" + email + "&pass=" + pass;

				if(loginValidation()) {
					login_xhr.open("POST", URL, true);

					login_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					login_xhr.send(queryString);
				}
			}		
		</SCRIPT>

	</HEAD>

	<BODY ONLOAD = "initAjax();">
		<? include("header.php"); ?>
		<MS CLASS = "title">LOGIN</MS><BR />
		<TABLE><TR>
		<TD>ID(Email): </TD>
		<TD><INPUT TYPE = "text" NAME = "email" ID = "email" ONKEYUP = "loginValidation();" ONKEYPRESS = "keypress(event.keyCode);" /><BR/>
		<MS CLASS = "alert" NAME = "emAlert" ID = "emAlert"></MS></TD></TR>
		<TR><TD>Pass: </TD>
		<TD><INPUT TYPE = "password" NAME = "pass" ID = "pass" ONKEYUP = "loginValidation();" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
		<MS CLASS = "alert" NAME = "pwAlert" ID = "pwAlert"></MS></TD></TR></TABLE>
		<INPUT CLASS = "g-bt" VALUE = "Login" TYPE = "button" onClick = "loginAjax();" />
	</BODY>
</HTML>