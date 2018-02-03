<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>

<?php
	$conn = include 'mysql_auth.php';


	$transaction = htmlspecialchars($_POST["transaction"]);
	$person = htmlspecialchars($_POST["person"]);
	$dollar_amount = htmlspecialchars($_POST["dollar_amount"]);
	$reason = htmlspecialchars($_POST["reason"]);
	$from_user = htmlspecialchars($_POST["from_user"]);
	$trans_choices = $_POST["trans-choices"];
	$user_name = htmlspecialchars($_POST["user_name"]);

	$their_ids = array();

	$sql = "INSERT INTO transaction (amount, owner, reason ) VALUES (".$dollar_amount.",".$from_user.", \"".$reason."\")";
	$result = $conn->query($sql);


	$res = $conn->query('SELECT LAST_INSERT_ID() FROM transaction');
	$row = $res->fetch_assoc();

	$last_id = json_encode($row['LAST_INSERT_ID()']);


	foreach($trans_choices as $choice)
	{
		$to_id=0;
		$from_id=0;

		if ($transaction == "Pay")
		{
			$to_id=$choice;
			$from_id=$from_user;
		}
		else
		{
			$to_id=$from_user;	
			$from_id=$choice;
		}

		$sql = "INSERT INTO transaction_list (transID, toId, fromId) VALUES (".$last_id.", ".$to_id.", ".$from_id.")";
		$conn->query($sql);
	}

	$conn->close();

?>
