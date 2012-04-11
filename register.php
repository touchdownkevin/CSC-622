<?php

//*** Fall 2011 Project -  sales systems
//*** register page

session_start();

if($_SESSION['login'] == "login") {
	echo "<SCRIPT>alert('You need to logout first'); ";
	echo "location.href = 'default.php'</SCRIPT>";
}

?>

<HTML>
	<HEAD>
		<LINK REL = "stylesheet" HREF = "css/general.css" TYPE = "text/css" MEDIA = "screen" />

		<SCRIPT TYPE = "text/javascript">

			var duplicatedID_xhr;			//*** Ajax object checkes if input ID is duplicated for register
			var register_xhr;				//*** Ajax object signs up

			function trim(str) {

				return str.replace(/^\s+/, '').replace(/\s+$/,'');
			}

			//*** validaing for register **********
			function registerValidation() {

				var bFlag = true;

				var fnameExpr = /^(\w+\s?)+$/i 				// validation for first name 
				var lnameExpr = /^\w+$/i 					// validation for last name
				var emailExpr = /^\w{3,20}(\.\w+){0,3}@\w+(\.\w+)+$/i	// validation for email
				var passExpr = /^\w{5,}$/i 					// validation for password
				var phoneExpr = /^\d{10}$/i					// validation for phone number
				var stateExpr = /^\w{2}$/i					// validation for state
				var zipcodeExpr = /^\d{5}$/i					// validation for zipcode

				var email = document.getElementById("email").value;
				var pass = document.getElementById("pass").value;
				var cpass = document.getElementById("cpass").value;
				var phone = document.getElementById("phone").value;
				var cphone = document.getElementById("cphone").value;
				var fname = document.getElementById("fname").value;
				var lname = document.getElementById("lname").value;
				var addr1 = document.getElementById("addr1").value;
				var city = document.getElementById("city").value;
				var state = document.getElementById("state").value;
				var zipcode = document.getElementById("zipcode").value;

				// check for email
				if(trim(email) == "") {
					document.getElementById("emailAlert").innerHTML = "* required to register";

					bFlag = false;
				} else if(trim(email).search(emailExpr) == -1) {
					bFlag = false;

					document.getElementById("emailAlert").innerHTML = "* not valid email address";
				} else
					document.getElementById("emailAlert").innerHTML = "";

				// check for password
				if(trim(pass) == "") {
					bFlag = false;

					document.getElementById("passAlert").innerHTML = "* required to register";
				} else if(trim(pass).search(passExpr) == -1) {
					bFlag = false;

					document.getElementById("passAlert").innerHTML = "* password should be at least 5 digits";
				} else
					document.getElementById("passAlert").innerHTML = "";

				// check for confirm password
				if(cpass != pass) {
					bFlag = false;

					document.getElementById("cpassAlert").innerHTML = "* different with new password";
				} else
					document.getElementById("cpassAlert").innerHTML = "";

				// check for phone number
				if(trim(phone) == "") {
					bFlag = false;

					document.getElementById("phoneAlert").innerHTML = "* required to register";
				} else if (trim(phone).search(phoneExpr) == -1) {
					bFlag = false;

					document.getElementById("phoneAlert").innerHTML = "* xxxxxxxxxxx (10 digits)";
				} else
					document.getElementById("phoneAlert").innerHTML = "";

				// check for first name
				if(trim(fname) == "") {
					bFlag = false;

					document.getElementById("fnameAlert").innerHTML = "* required to register";
				} else if(trim(fname).search(fname) == -1) {
					bFlag = false;

					document.getElementById("fnameAlert").innerHTML = "* not valid first name";
				} else
					document.getElementById("fnameAlert").innerHTML = "";

				// check for last name
				if(trim(lname) == "") {
					bFlag = false;

					document.getElementById("lnameAlert").innerHTML = "* required to register";
				} else if(trim(lname).search(lname) == -1) {
					bFlag = false;

					document.getElementById("lnameAlert").innerHTML = "* not valide last name";
				} else
					document.getElementById("lnameAlert").innerHTML = "";

				// check for address 1
				if(trim(addr1) == "") {
					bFlag = false;

					document.getElementById("addr1Alert").innerHTML = "* required to register";
				} else
					document.getElementById("addr1Alert").innerHTML = "";

				// check for city
				if(trim(city) == "") {
					bFlag = false;

					document.getElementById("cityAlert").innerHTML = "* required to register";
				} else
					document.getElementById("cityAlert").innerHTML = "";

				// check for state
				if(trim(state) == "") {
					bFlag = false;

					document.getElementById("stateAlert").innerHTML = "* required to register";
				} else if(trim(state).search(stateExpr) == -1) {
					bFlag = false;

					document.getElementById("stateAlert").innerHTML = "* not valid state (2 letters)";
				} else
					document.getElementById("stateAlert").innerHTML = "";

				// check for zipcode
				if(trim(zipcode) == "") {
					bFlag = false;

					document.getElementById("zipcodeAlert").innerHTML = "* required to register";
				} else if(trim(zipcode).search(zipcodeExpr) == -1) {
					bFlag = false;

					document.getElementById("zipcodeAlert").innerHTML = "* not valid zipcode";
				} else
					document.getElementById("zipcodeAlert").innerHTML = "";

				return bFlag;
			}

			function keypress(key) {

				if(key == 13)
					checkDuplicatedIDAjax();
			}

			//*** Ajax initializing *************************************************************
			function initAjax() {

				register_xhr = new XMLHttpRequest();	//*** initialize register
				duplicatedID_xhr = new XMLHttpRequest();		//*** initialize duplicated_ID

				//*** register
				register_xhr.onreadystatechange=function() {
					if((register_xhr.readyState == 4) && (register_xhr.status == 200)) {

						var message = register_xhr.responseText;

						if(trim(message) == "OK")
							location.href = "default.php";
					}
				}

				//*** duplicated ID
				duplicatedID_xhr.onreadystatechange=function() {
	
					if((duplicatedID_xhr.readyState == 4) && (duplicatedID_xhr.status == 200)) {

						var message = duplicatedID_xhr.responseText;

						if(trim(message) == "OK")
							registerAjax();
						else
							document.getElementById("emailAlert").innerHTML = "* email id duplicated";
					}
				}
			}

			//*** Ajax caller to check duplicated ID for register
			function checkDuplicatedIDAjax() {

				var URL = "php/duplicated_ID.php";

				var queryString = "email=" + document.getElementById("email").value;

				if(registerValidation()) {
					duplicatedID_xhr.open("POST", URL, true);

					duplicatedID_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
					duplicatedID_xhr.send(queryString);
				}
			}

			//*** Ajax caller to register
			function registerAjax() {

				var URL = "php/register.php";

				var email = document.getElementById("email").value;
				var pass = document.getElementById("pass").value;
				var phone = document.getElementById("phone").value;
				var cphone = document.getElementById("cphone").value;
				var fname = document.getElementById("fname").value;
				var mname = document.getElementById("mname").value
				var lname = document.getElementById("lname").value;
				var addr1 = document.getElementById("addr1").value;
				var addr2 = document.getElementById("addr2").value;
				var city = document.getElementById("city").value;
				var state = document.getElementById("state").value;
				var zipcode = document.getElementById("zipcode").value;

				var queryString = "email=" + email + "&pass=" + pass + "&phone=" + phone + "&cphone=" + cphone + "&fname=" + fname + "&mname=" + mname + 
					"&lname=" + lname + "&addr1=" + addr1 + "&addr2=" + addr2 + "&city=" + city + "&state=" + state + "&zipcode=" + zipcode;

				register_xhr.open("POST", URL, true);

				register_xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				register_xhr.send(queryString);
			}
		</SCRIPT>
	</HEAD>

	<BODY ONLOAD = "initAjax();">
		<? include("header.php") ?>
		<MS CLASS = "title">REGISTER</MS><BR />
		<TABLE>
		<TR><TD>* Email(Will be used for login ID): </TD>
		<TD><INPUT TYPE = "text" ID = "email" SIZE = "32" ONKEYUP = "registerValidation();" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "emailAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>* Password: </TD>
		<TD><INPUT TYPE = "password" ID = "pass" SIZE = "32" ONKEYUP = "registerValidation();" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "passAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>* Confirm Password: </TD>
		<TD><INPUT TYPE = "password" ID = "cpass" SIZE = "32" ONKEYUP = "registerValidation();" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "cpassAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>* Phone: </TD>
		<TD><INPUT TYPE = "text" ID = "phone" SIZE = "32" ONKEYUP = "registerValidation();" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "phoneAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>Cell Phone: </TD>
		<TD><INPUT TYPE = "text" ID = "cphone" SIZE = "32" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "cphoneAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>* First Name: </TD>
		<TD><INPUT TYPE = "text" ID = "fname" SIZE = "32" ONKEYUP = "registerValidation();" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "fnameAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>Middle Name: </TD>
		<TD><INPUT TYPE = "text" ID = "mname" SIZE = "32" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "mnameAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>* Last Name: </TD>
		<TD><INPUT TYPE = "text" ID = "lname" SIZE = "32" ONKEYUP = "registerValidation();" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "lnameAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>* Address 1: </TD>
		<TD><INPUT TYPE = "text" ID = "addr1" SIZE = "32" ONKEYUP = "registerValidation();" ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "addr1Alert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>Address 2: </TD>
		<TD><INPUT TYPE = "text" ID = "addr2" SIZE = "32" ONKEYUP = "registerValidation(); " ONKEYPRESS = "keypress(event.keyCode);" /></TD></TR>
		<TR><TD>* City: </TD>
		<TD><INPUT TYPE = "text" ID = "city" SIZE = "32" ONKEYUP = "registerValidation(); " ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "cityAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>* State: </TD>
		<TD><INPUT TYPE = "text" ID = "state" SIZE = "5" ONKEYUP = "registerValidation(); " ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "stateAlert" CLASS = "alert"></MS></TD></TR>
		<TR><TD>* Zipcode: </TD>
		<TD><INPUT TYPE = "text" ID = "zipcode" SIZE = "5" ONKEYUP = "registerValidation(); " ONKEYPRESS = "keypress(event.keyCode);" /><BR />
			<MS ID = "zipcodeAlert" CLASS = "alert"></MS></TD></TR></TABLE>
		<INPUT TYPE = "button" CLASS = "g-bt" ID = "reg_button" value = "Submit" onClick = "checkDuplicatedIDAjax();"><BR />
		* indicates 'REQUIRED'
	</BODY>
</HTML>