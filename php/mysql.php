<?php

define("MYSQL_DEFINE", "OK");

//*** Fall 2011 Project -  sales systems
//*** mysql database connection

	//**********************************************************************
	//*** connect to mysql and select a db
	//**********************************************************************
	function connect() {

		$dbhost = "localhost";
		$dbuser = "louis";
		$dbpass = "fall2011";
		$dbname = "louisdb";

		$conn = mysql_connect($dbhost, $dbuser, $dbpass)
			or die (mysql_error());

		mysql_select_db($dbname)
			or die (mysql_error());

		return $conn;
	}

?>

<?php
	//*************************************************************
	//*** connect to mysql and run the one-row-result select query
	//*************************************************************

	function executeSQL1($dbquery) {
		//*** connect to mysql
		$conn = connect();

		//*** execute the query
		$result = mysql_query($dbquery);

		//*** die if no result
		if(!$result)
			die("Query Failed.");

		$row = mysql_fetch_row($result);

		if($row)
			foreach($row as $item)
				$query_result = $query_result." ".$item;

		//*** free the resources associated with the result
		mysql_free_result($result);

		//*** close this connection
		mysql_close($conn);

		return $query_result;
	}

	//*************************************************************
	//*** connect to mysql and run the update query
	//*************************************************************
	function executeSQL2($dbquery) {
		//*** connect to mysql
		$conn = connect();

		//*** execute the query
		mysql_query($dbquery);

		//*** close this connection
		mysql_close($conn);
	}


	//*************************************************************
	//*** connect to mysql and run the multiple-row-result select query
	//*************************************************************
	function executeSQL3 ($dbquery) {
		//*** connect to mysql
		$conn = connect();

		//*** execute the query
		$result = mysql_query($dbquery);

		//*** die if no result
		if (!$result)
			die("Query Failed.");

		$query_result = "";

		while ($row = mysql_fetch_row($result)) {
			if ($row) {
				foreach ($row as $item)
					$query_result = $query_result."&;&".$item;
				$query_result = $query_result."&&;&&";
			}
		}

		//*** Free the resources associated with the result
		mysql_free_result($result);

		//*** close this connection
		mysql_close($conn);

		return $query_result;
	}

	//*************************************************************
	//*** connect to mysql and run the one-row-result select query
	//*************************************************************

	function executeSQL4($dbquery) {
		//*** connect to mysql
		$conn = connect();

		//*** execute the query
		$result = mysql_query($dbquery);

		//*** die if no result
		if(!$result)
			die("Query Failed.");

		$row = mysql_fetch_row($result);

		if($row)
			foreach($row as $item)
				$query_result = $query_result."&;&".$item;

		//*** free the resources associated with the result
		mysql_free_result($result);

		//*** close this connection
		mysql_close($conn);

		return $query_result;
	}

	//*************************************************************
	//*** connect to mysql and run the multiple-row-result but one select query
	//*************************************************************
	function executeSQL5 ($dbquery) {
		//*** connect to mysql
		$conn = connect();

		//*** execute the query
		$result = mysql_query($dbquery);

		//*** die if no result
		if (!$result)
			die("Query Failed.");

		$query_result = "";

		while ($row = mysql_fetch_row($result)) {
			if ($row) {
				foreach ($row as $item)
					$query_result = $query_result.$item;
				$query_result = $query_result."&&;&&";
			}
		}

		//*** Free the resources associated with the result
		mysql_free_result($result);

		//*** close this connection
		mysql_close($conn);

		return $query_result;
	}
?>