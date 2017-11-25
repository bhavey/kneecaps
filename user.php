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
					echo $current_user."'s Page";
				?>
				 <a href="/">(Go back)</a>
			</span>
		</div>
		<div class="content_section floating_element">
			I am owed <span id="what-im-owed" class="payment"></span>,
			I owe <span id="what-i-owe" class="charge"></span>.
			<form action="/transaction.php" onsubmit="return validateForm()" name="userform">
				I want to 
				<select name="transaction" id="trans_type">
					<option value="Pay">Pay</option>
					<option value="Charge">Charge</option>
				</select>


				<select name="person" id="person_selection">
					<option value="Everyone">Everyone</option>
			<?php
				// Get our MySQL connection.
				$conn = include 'mysql_auth.php';
				$sql = "SELECT * FROM person";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
    				while($row = $result->fetch_assoc()) {
    					if ($row["name"] == $current_user)
    					{
							$current_id = $row["id"];
						}
						else
						{
							// Fill out everyone else in the drop box.
							echo "<option value=\"".$row["name"]."\">".$row["name"]."</option>";
						}
					}
				}
			?>
				</select>

				$<input type="text" id="thingy" name="dollar_amount">
				for 
				<input type="text" name="reason" id="charge_reason">

			<?php
				echo "<input type=\"hidden\" name=\"from_user\" value=\"".$current_user."\">"
			?>
				<input type="submit" value="GO">

			</form>
			<br><br>

			<table class="transaction-table">
				<tr>
					<th>Delete?</th>
					<th>Date</th>
					<th>$ Amount</th>
					<th>From/To</th>
					<th>Description</th>
				</tr>					
			<?php
//				$conn = include 'mysql_auth.php';
				$totalPositive = 0;
				$toalNegative = 0;
				$sql = "SELECT * FROM transaction WHERE isActive=1 AND (toUser=\"" . $current_id . "\" OR fromUser=\"" . $current_id . "\")";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// output data of each row
    				while($row = $result->fetch_assoc()) {
    					echo "<tr class=\"";
    					$is_payment = 0;
    					if ($row['toUser'] == $current_id)
    					{
    						$is_payment = 1;
    					}

    					if ($is_payment)
    					{
    						$totalPositive = $totalPositive + $row["amount"];
							$sql = "SELECT * FROM person WHERE id=\"" . $row["fromUser"] . "\"";
							$result2 = $conn->query($sql);
							if ($result2->num_rows > 0) {
								$row2 = $result2->fetch_assoc();
							}
    						echo "payment";
    					}
    					else
    					{
    						$totalNegative = $totalNegative + $row["amount"];
							$sql = "SELECT * FROM person WHERE id=\"" . $row["toUser"] . "\"";
							$result2 = $conn->query($sql);
							if ($result2->num_rows > 0) {
								$row2 = $result2->fetch_assoc();
							}
    						echo "charge";
    					}
						$other_name = $row2["name"];
    					echo "\">";
    					$phpdate = strtotime($row["timestamp"]);
    					echo "<td id=\"trans-".$row["id"] ."\" style=\"color:red\"><a href=\"#\">" . x . "</a></td>";
    					echo "<td>" . date("m/d/y", $phpdate) . "</td>";
    					echo "<td>$" . number_format($row["amount"],2) . "</td>";

    					echo "<td>" . $other_name . "</td>";
    					echo "<td>" . $row["reason"] . "</td>";
        				echo "</tr>";
    				}
				}

				$conn->close();
			?>
			</table>
			<br><br><br>
		    Alpha Build 2017
		</div>
	</body>
</html>

<script>
$("[id*=trans-]").click(function() {
	var id = $(this).attr("id").split('-')[1];
	var r = confirm("Are you sure you want to remove this entry? It will also disappear on the other person's page.");
	if (r == true)
	{
		$.post("delete.php", { id: id, from_user: "<?php echo $current_user; ?>" })
		.done(function() {
			location.reload();
		})
		.fail(function() {
			alert("Failed to remove entry.");
		});
	}
});

var total_positive="$"+<?php echo $totalPositive ?>;
var total_negative="$"+<?php echo $totalNegative ?>;

$("[id='what-im-owed']").text(total_positive);
$("[id='what-i-owe']").text(total_negative);


</script>
