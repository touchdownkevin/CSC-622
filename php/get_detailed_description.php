<?php

session_start();
include("mysql.php");

//*** Fall 2011 Project -  sales systems
//*** get detailed description page

// illegal access
if(!isset($_POST["cate_id"]))
	echo "<script>location.href = '../default.php'</script>";

$cate_id = $_POST["cate_id"];

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

	mysql_free_result($result2);
	mysql_close($conn);
}

print $return;

?>