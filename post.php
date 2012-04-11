<?php

//*** Fall 2011 Project -  sales systems
//*** post item

session_start();
include("php/mysql.php");

//*** illegal access
if(!isset($_REQUEST["name"]))
	echo"<script>location.href = 'default.php'</script>";

$cate_id = $_REQUEST["sub_cate"];
$name = $_REQUEST["name"];
$condition = $_REQUEST["condition"];
$amount = $_REQUEST["amount"];
$buynow = $_REQUEST["buynow"];
$bidding = $_REQUEST["bidding"];
$days = $_REQUEST["days"];
$hrs = $_REQUEST["hrs"];
$mins = $_REQUEST["mins"];
$description = $_REQUEST["description"];
$details = split(",", $_REQUEST["detail"]);
$file_name = "default.jpg";
$file_path = "itemImage/";

$item_id = 0;
$newPeriod = date("Y-m-d H:i:s", time() + ($days * 24 * 60 * 60) + ($hrs * 60 * 60) + ($mins * 60));

if($buynow == "")
	$buynow = 0;
if($bidding == "")
	$bidding = 0;

//*** get next item_id
$query1 = "select max(item_id) from Items";
$result1 = executeSQL1($query1);

if($result1 != null)
	$item_id = $result1 + 1;

//*** check files and upload
if(isset($_FILES["itemImage"]["size"])) {

	if($_FILES["itemImage"]["size"] > 1000000) {
		echo "<script>alert('File too large!'); location.href = 'default.php'</script>";
		exit;
	}

	$file_ext = end(explode(".", $_FILES["itemImage"]["name"]));

	if($file_ext != "jpg" && $file_ext != "gif") {
		echo "<script>alert('JPG or GIF only!'); location.href = 'default.php'</script>";
		exit;
	}

	$file_name = $item_id.".".$file_ext;

	if(!move_uploaded_file($_FILES["itemImage"]["tmp_name"], $file_path.$file_name)) {
		echo "<script>alert('Error occured while file uploading!'); location.href = 'default.php'</script>";
		exit;
	}

	//*** file resizing
	/*$origin_img;

	if($file_ext == "gif")
		$origin_img = ImageCreateFromGIF($file_path."origin_".$file_name);
	else
		$origin_img = @imagecreatefromjpeg($file_path."origin_".$file_name);

	$img_info = getImageSize($file_path."origin_".$file_name);

	$thumb_img = imagecreatetruecolor(100, 100);

	imagecopyresampled($thumb_img, $origin_img, 0, 0, 0, 0, 100, 100, $img_info[0], $img_info[1]);

	if($file_ext == "gif")
		ImageGIF("thumb_img", $file_path.$file_name);
	else
		ImageJPEG("thiumb_img", $file_path.$file_name); */
}

//*** insert into Items
$query2 = "insert into Items values ($item_id, $cate_id, '$name', '$condition', $amount, '$description', '$file_name')";
executeSQL2($query2);

//*** find sub table
$query3 = "select cate_name from Categories where cate_id = $cate_id";
$result3 = executeSQL1($query3);

//*** insert into sub table
$query4 = "insert into $result3 (item_id";

for($i = 0; $i < count($details); $i++) {
	$query4 = $query4.", ".$details[$i];
}

$query4 = $query4.") values ($item_id";

for($i = 0; $i < count($details); $i++) {
	$query4 = $query4.", '". $_REQUEST[$details[$i]]."'";
}

$query4 = $query4.")";
executeSQL2($query4);

//*** insert into Posts
$query5 = "insert into Posts values ('".$_SESSION['ID']."', $item_id, $buynow, $bidding, '$newPeriod', 'active')";
executeSQL2($query5);

exec("php/process.php $item_id > /dev/null &");

echo "<script>alert('Posting success!')</script>";
echo "<script>location.href = 'default.php'</script>";

?>