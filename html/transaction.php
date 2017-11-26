<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>
		<?php
			$current_user = htmlspecialchars($_GET["from_user"]);
			echo $current_user;
    	?>'s Page
    </title>
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	<div class="main_page">
		<div class="page_header floating_element">
			<img src="/icons/kneecaps.jpg" alt="Gimme my money" class="floating_element"/>
			<span class="floating_element">
				<?php 
					echo $current_user."'s Page"
				?>
			</span>
		</div>
		<div class="content_section floating_element">
			This page should automatically go back. If it hasn't click
			<a href="/user.php?user=<?php echo $current_user;?>">here</a>
			<br><br><br>
		    Alpha Build 2017
		</div>
	</body>
</html>

<?php
	$conn = include 'mysql_auth.php';
	$transaction = htmlspecialchars($_GET["transaction"]);
	$person = htmlspecialchars($_GET["person"]);
	$dollar_amount = htmlspecialchars($_GET["dollar_amount"]);
	$reason = htmlspecialchars($_GET["reason"]);
	$from_user = htmlspecialchars($_GET["from_user"]);
	$trans_choices = $_GET["trans-choices"];

	$their_ids = array();

//	INSERT INTO transaction(amount, owner, reason) VALUES (725.00, 1, "Nov Rent");



	$sql = "INSERT INTO transaction (amount, owner, reason ) VALUES (".$dollar_amount.",".$from_user.", \"".$reason."\")";
	$result = $conn->query($sql);

//	echo $sql;

	echo "this guy";

	$res = $conn->query('SELECT LAST_INSERT_ID()');

	$row = $res->fetch_assoc();
	echo "that guy";
//	$row = $conn->fetch_array($res);
	echo "even this guy";
//	$lastInsertId = $row[0];

	echo "last insert id:";
	$last_id = json_encode($row['LAST_INSERT_ID()']);


	foreach($trans_choices as $choice)
	{

//INSERT INTO transaction_list(transId, toId, fromId) VALUES(5, 1, 2);


//		$sql = "INSERT INTO transaction (transID, toId, fromId) VALUES (".$dollar_amount.", ".$to_id.", ".$from_id.", \"".$reason."\")";
//		$conn->query($sql);

	}
	$conn->close();
*/

?>
<script>
trans_choices= <?php echo json_encode($trans_choices); ?>;
console.log("trans_choices: ");
console.log(trans_choices);
//	document.location = "/user.php?user=<?php echo $current_user;?>";
</script>