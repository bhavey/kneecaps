<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>
		<?php
			$current_user = htmlspecialchars($_GET["user"]);
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
			<form action="/transaction.php" id="userform">
				I want to 
				<select name="transaction" id="trans_type">
					<option value="Pay">Pay</option>
					<option value="Charge">Charge</option>
				</select>

				<select name="person" id="person_selection">
					<option value="Everyone">Everyone</option>
					<option value="Ben">Ben</option>
					<option value="Thomaso">Thomaso</option>
					<option value="Luke">Luke</option>
				</select>

				$<input type="text" name="amount" id="dollar_amount">
				for 
				<input type="text" name="reason" id="charge_reason">

				<input type="submit" value="GO">

			</form>
			<br><br><br>
		    Alpha Build 2017
		</div>
	</body>
</html>

<script>
$("#trans_type").change(function() {
	if ($("#trans_type").val() == "Pay")
	{

	}
	else
	{
	}

});
</script>