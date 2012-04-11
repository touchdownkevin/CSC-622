#!/opt/local.maxwell/php-5.3.3/bin/php -q
<?php

include("mysql.php");

$time = date("Y-m-d H:i:s", time());
$item_id = $argv[1];
$header  = "Content-type: text/html; charset=iso-8859-1";

$queryt = "insert into kkk values('$time', '$item_id')";
executeSQL2($queryt);

//*** calculate sleep time
$query1 = "select unix_timestamp(period) - unix_timestamp(NOW()) from Posts where item_id = $item_id";
$result1 = executeSQL1($query1);

if($result1 > 0)
	sleep($result1);

//*** get top bidding price
$query2 = "select max(price) from Bid where item_id = $item_id";
$result2 = trim(executeSQL1($query2));

$query3 = "select Posts.email, Items.name, Posts.status from Posts, Items where Posts.item_id = $item_id and Items.item_id = $item_id";
$result3 = split("&;&", executeSQL4($query3));

if(trim($result3[3]) == "active") {
	$query4 = "update Posts set status = 'end' where item_id = $item_id";
	executeSQL2($query4);

	//*** No Winner
	if($result2 == "") {
	
		$message = "Your item, <a href = 'http://maxwell.sju.edu/~jk454218/fall2011/item.php?itemID=$item_id'>$result3[2]</a> ended auction.";
		$message = $message."<br />Unfortunately, however, there is no winner.<br />We regret to inform you this.";

		mail($result3[1], "Your Auction Ended", $message, $header);
	//*** Winner and Losers
	} else {
		$query5 = "select email from Bid where item_id = $item_id and price = $result2";
		$result5 = trim(executeSQL1($query5));

		$query6 = "select distinct(email) from Bid where item_id = $item_id and email <> '$result5'";
		$result6 = split("&&;&&", executeSQL5($query6));

		$message1 = "Your item, <a href = 'http://maxwell.sju.edu/~jk454218/fall2011/item.php?itemID=$item_id'>$result3[2]</a> ended auction.";
		$message1 = $message1."<br />Congratulations! You have the winner, $result5, with \$$result2";

		$message2 = "Congratulations! You won the item, <a href = 'http://maxwell.sju.edu/~jk454218/fall2011/item.php?itemID=$item_id'>$result3[2]</a>";

		$message3 = "Sorry, you didn't win the item, <a href = 'http://maxwell.sju.edu/~jk454218/fall2011/item.php?itemID=$item_id'>$result3[2]</a>";

		mail($result3[1], "Your Winner", $message1, $header);
		mail($result5, "You won the item, $result3[2]", $message2, $header);

		for($i = 1; $i < count($result6) - 1; $i++) {
			mail($result6[$i], "You didn't win the item, $result3[2]", $message3, $header);

		}

		//*** insert winner's information
		$query10 = "select max(purchase_id) from Purchases";
		$result10 = trim(executeSQL1($query10));

		if($result10 == "")
			$result10 = 1;
		else
			$result10 = $result10 + 1;

		$query11 = "insert into Purchases(purchase_id, price, amount, status) values($result10, $result2, 1, 'Won')";
		executeSQL2($query11);

		$query12 = "insert into Won(purchase_id, item_id) values($result10, $item_id)";
		executeSQL2($query12);

		$query13 = "insert into Buyer(purchase_id, email, rating, comment, status) values($result10, '$result5', 0, '', 'wating')";
		executeSQL2($query13);

		$query14 = "insert into Seller(purchase_id, email, rating, comment, status) values($result10, '$result3[1]', 0, '', 'wating')";
		executeSQL2($query14);
	}
} 

?>