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

	$their_ids = array();

	$sql = "SELECT * FROM person";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
    	while($row = $result->fetch_assoc()) {
    		if ($row["name"] == $from_user)
    		{
				$my_id = $row["id"];
			}
			else
			{
				if ($person == "Everyone")
				{
					array_push($their_ids, $row["id"]);
				}
				else if ($person == $row["name"])
				{
					array_push($their_ids, $row["id"]);
				}
			}
		}
	}

	for ($i = 0, $size = count($their_ids); $i < $size; ++$i)
	{
		$their_id = $their_ids[$i];
		$from_id = 0;
		$to_id = 0;
		if ($transaction == "Pay")
		{
			$to_id = $their_id;
			$from_id = $my_id;
		}
		else
		{
			$to_id = $my_id;
			$from_id = $their_id;
		}
		$sql = "INSERT INTO transaction (amount, toUser, fromUser, reason ) VALUES (".$dollar_amount.", ".$to_id.", ".$from_id.", \"".$reason."\")";
		$conn->query($sql);
	}

	$conn->close();
?>
<script>
	document.location = "/user.php?user=<?php echo $current_user;?>";
</script>