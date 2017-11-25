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
			<form action="/transaction.php" name="userform">
				I want to 
				<select name="transaction" id="trans_type">
					<option value="Pay">Pay</option>
					<option value="Charge">Charge</option>
				</select>

<!--
				<select name="person" id="person_selection">
					<option value="Everyone">Everyone</option>
-->
<div class="inline-checkboxes">
			<?php
				echo "<input type=\"checkbox\" name=\"trans-choices\" value=\"Everyone\">Everyone";
				echo "<br>";
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
//							echo "<option value=\"".$row["name"]."\">".$row["name"]."</option>";
							echo "<input type=\"checkbox\" name=\"trans-choices\" value=\"".$row[""]."\">".$row["name"];
							echo "<br>";
						}
					}
				}
			?>
</div>
<!--
				</select>
-->

				$<input type="text" id="thingy" style="width:50px;" name="dollar_amount">
				for 
				<input type="text" name="reason" id="charge_reason">

			<?php
				echo "<input type=\"hidden\" name=\"from_user\" value=\"".$current_user."\">"
			?>
				<input type="submit" value="GO">

			</form>
			<br>
			<span id="total-prefix"><span id="total-amount">$</span></span>
			<br><br>
			<table class="transaction-table">
				<tr>
				<?php
					$sql = "SELECT * FROM person";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						if ($row["id"] != $current_id)
						{
							while($row = $result->fetch_assoc()) {
								echo "<th>".$row["name"]."</th>";
							}
						}
					}
				?>
				</tr>
				<tr>
				<?php
					$sql = "SELECT * FROM person";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						if ($row["id"] != $current_id)
						{
							while($row = $result->fetch_assoc()) {
								echo "<td id=\"total-".$row["id"]."\">$0</td>";
							}
						}
					}
				?>
				</tr>
			</table>
			<br>

			<table class="transaction-table">
				<tr>
					<th>Delete?</th>
					<th>Date</th>
					<th>$ Amount</th>
					<th>From/To</th>
					<th>Description</th>
				</tr>					
			<?php
				$totalPositive = 0;
				$totalNegative = 0;
				$totalUsers = array();
				$sql = "SELECT * FROM transaction WHERE isActive=1 AND (toUser=\"" . $current_id . "\" OR fromUser=\"" . $current_id . "\") ORDER BY ID DESC";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					// output data of each row
    				while($row = $result->fetch_assoc()) {
    					$prev_row=$row;
    					echo "<tr class=\"";
    					$is_payment = 0;


    					if ($row['toUser'] == $current_id)
    					{
    						$is_payment = 1;
    					}

    					if ($is_payment)
    					{
	    					if (!$totalUsers[$row['fromUser']])
	    					{
	    						$totalUsers[$row['fromUser']]=0;
	    					}
	    					$totalUsers[$row['fromUser']]=$totalUsers[$row['fromUser']]+$row["amount"];
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
	    					if (!$totalUsers[$row['toUser']])
	    					{
	    						$totalUsers[$row['toUser']]=0;
	    					}
	    					$totalUsers[$row['toUser']]=$totalUsers[$row['toUser']]-$row["amount"];
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
    					echo "<td id=\"trans-".$row["id"] ."\" style=\"color:black\"><a href=\"#\">" . x . "</a></td>";
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

var total_positive=<?php echo $totalPositive ?>;
var total_negative=<?php echo $totalNegative ?>;

console.log("This many... ");
console.log(<?php echo $totalUsers[2]; ?>);

$("[id='what-im-owed']").append(total_positive);
$("[id='what-i-owe']").append(total_negative);

<?php $jsonData = json_encode($totalUsers); ?>
var json_data = <?php echo $jsonData; ?>;

console.log(json_data);

for (var key in json_data)
{
	var is_negative=false;
	if (json_data[key] < 0)
	{
		is_negative = true;
	}
	var needed_id="[id='total-"+key+"']";
	if (is_negative)
	{
		$(needed_id).addClass('charge');
		$(needed_id).text("$"+(json_data[key]*-1));
	}
	else
	{
		$(needed_id).addClass('payment');
		$(needed_id).text("$"+json_data[key]);
	}
}

if (total_positive > total_negative)
{
	var total_difference=total_positive-total_negative;
	$("[id='total-prefix']").prepend(' People owe me a total of ');
	$("[id='total-amount']").append(Number(total_difference).toFixed(2));
	$("[id='total-amount']").addClass('payment');
	$("[id='total-prefix']").append('.');
}
else
{
	var total_difference=total_negative-total_positive;
	$("[id='total-prefix']").prepend(' I owe people a total of ');
	$("[id='total-amount']").append(Number(total_difference).toFixed(2));
	$("[id='total-amount']").addClass('charge');
	$("[id='total-prefix']").append('.');
}

</script>
