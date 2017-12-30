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

			<div class="inline-checkboxes">
			<?php
//				echo "<input type=\"checkbox\" name=\"trans-choices\" value=\"0\">Everyone";
//				echo "<br>";
				// Get our MySQL connection.
				$conn = include 'mysql_auth.php';
				$sql = "SELECT * FROM person WHERE is_active=1";
				$result = $conn->query($sql);

				$name_row = array();
				if ($result->num_rows > 0) {
    				while($row = $result->fetch_assoc()) {
    					$name_row[$row["id"]] = $row["name"];
    					if ($row["name"] == $current_user)
    					{
							$current_id = $row["id"];
						}
						else
						{
							// Fill out everyone else in the drop box.
//							echo "<option value=\"".$row["name"]."\">".$row["name"]."</option>";
							echo "<input type=\"checkbox\" name=\"trans-choices[]\" value=\"".$row["id"]."\">".$row["name"];
							echo "<br>";
						}
					}
				}
			?>
			</div>

				$<input type="text" id="thingy" style="width:50px;" name="dollar_amount">
				for 
				<input type="text" name="reason" id="charge_reason">

			<?php
				echo "<input type=\"hidden\" name=\"from_user\" value=\"".$current_id."\">";
				echo "<input type=\"hidden\" name=\"user_name\" value=\"".$current_user."\">";
			?>
				<input type="submit" value="GO">

			</form>
			<br>
			<span id="total-prefix"><span id="total-amount">$</span></span>
			<br><br>
			<table class="transaction-table">
				<tr>
				<?php
					$sql = "SELECT * FROM person WHERE is_active=1 AND id!=".$current_id;
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
					$sql = "SELECT * FROM person WHERE is_active=1 AND id!=".$current_id;
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

				// lol sorry
				$sql = "SELECT tran.*, tlist.toId, tlist.fromId, tlist.is_active FROM transaction tran INNER JOIN transaction_list tlist ON tlist.transId = tran.id INNER JOIN person p ON tlist.toId = p.id WHERE tran.is_active=1 AND tlist.is_active=1 AND (tlist.fromId=" . $current_id . " OR tlist.toId=" . $current_id . ") ORDER BY tlist.id DESC";

				$result = $conn->query($sql);

				$total_amounts = array();
				$total_from_others = array();

				if ($result->num_rows > 0) {
					// output data of each row
					$row_iter = 0;
    				while($row = $result->fetch_assoc()) {
    					$rows[$row_iter] = $row;
    					$row_iter = $row_iter + 1;
    				}

    				$running_value = 0;
    				$running_names = "";
    				for ($i = 0; $i < count($rows); $i++)
    				{
    					$row = $rows[$i];
    					$is_payment = 0;
    					if ($row['toId'] == $current_id)
    					{
    						$is_payment = 1;
    					}
    					if ($i != count($rows)-1)
    					{
                            $next_row = $rows[$i+1];
                            if ($next_row['id'] == $row['id'])
                            {
                            	if ($row['toId']==$current_id)
                            	{
	                            	$running_value = $running_value+$row['amount'];
	                            }
	                            else
	                            {
	                            	$running_value = $running_value-$row['amount'];
	                            }

                            	if ($is_payment)
                            	{
	                            	$running_names = $running_names.$name_row[$row['fromId']]." / ";
	                            	$total_from_others[$row['fromId']] = $total_from_others[$row['fromId']] + $row['amount'];
                            	}
                            	else
                            	{
	                            	$running_names = $running_names.$name_row[$row['toId']]." / ";
	                            	$total_from_others[$row['toId']] = $total_from_others[$row['toId']] + ($row['amount'] * -1);
                            	}
                            	continue;
                            }
    					}
                       	$running_value = $running_value+$row['amount'];

/*
                        if ($is_payment)
                        {
                        	$total_amounts[$row['id']]=$running_value;
                        	$running_names = $running_names.$name_row[$row['fromId']];
                        	if ($row['owner']==$current_id)
                        	{
		                        $total_from_others[$row['fromId']] = $total_from_others[$row['fromId']] + $row['amount'];
                        	}
                        	else
                        	{
		                        $total_from_others[$row['fromId']] = $total_from_others[$row['fromId']] - $row['amount'];                        		
                        	}
    					}
    					else
    					{
                        	$total_amounts[$row['id']]=$running_value*-1;
                        	$running_names = $running_names.$name_row[$row['toId']];
                        	if ($row['owner']==$current_id)
                        	{
		                       	$total_from_others[$row['toId']] = $total_from_others[$row['toId']] + $row['amount'];
		                    }
		                    else
		                    {
		                       	$total_from_others[$row['toId']] = $total_from_others[$row['toId']] - $row['amount'];		                    	
		                    }

    					}
    					*/


                        if ($is_payment)
                        {
                        	$total_amounts[$row['id']]=$running_value;
                        	$running_names = $running_names.$name_row[$row['fromId']];
                        	if ($row['toId']==$current_id)
                        	{
		                        $total_from_others[$row['fromId']] = $total_from_others[$row['fromId']] + $row['amount'];
                        	}
                        	else
                        	{
		                        $total_from_others[$row['fromId']] = $total_from_others[$row['fromId']] - $row['amount'];                        		
                        	}
    					}
    					else
    					{
                        	$total_amounts[$row['id']]=$running_value*-1;
                        	$running_names = $running_names.$name_row[$row['toId']];
                        	if ($row['toId']==$current_id)
                        	{
		                       	$total_from_others[$row['toId']] = $total_from_others[$row['toId']] + $row['amount'];
		                    }
		                    else
		                    {
		                       	$total_from_others[$row['toId']] = $total_from_others[$row['toId']] - $row['amount'];		                    	
		                    }

    					}

    					echo "<tr class=\"";
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
                        	if ($row['toId']==$current_id)
							{
    							echo "payment";
    						}
    						else
    						{
    							echo "charge";
    						}
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
                        	if ($row['toId']==$current_id)
							{
	    						echo "payment";
	    					}
	    					else
	    					{
	    						echo "charge";
	    					}

    					}
						$other_name = $row2["name"];
    					echo "\">";
    					$phpdate = strtotime($row["timestamp"]);
    					echo "<td id=\"trans-".$row["id"] ."\" style=\"color:black\"><a href=\"#\">" . x . "</a></td>";    						
    					echo "<td>" . date("m/d/y", $phpdate) . "</td>";
    					echo "<td>$" . number_format($running_value,2) . "</td>";

    					echo "<td>" . $running_names . "</td>";
    					echo "<td>" . $row["reason"] . "</td>";
        				echo "</tr>";
        				$running_value=0;
	    				$running_names="";
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
	var user_id = <?php echo $current_id; ?>;
	console.log("user_id: ");
	console.log(user_id);
	console.log("this id: ");
	console.log(id);
	var r = confirm("You only delete an entry made by mistake. If you've paid off your portion of a bill use the toolbar above to record it as a payment instead of deleting the entry itself.");
	if (r == true)
	{
		result = $.post("delete.php", { id: id, user_id: user_id, from_user: "<?php echo $current_user; ?>" })
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


$("[id='what-im-owed']").append(total_positive);
$("[id='what-i-owe']").append(total_negative);

data_payload=<?php echo json_encode($rows); ?>;
name_row=<?php echo json_encode($name_row) ?>;

total_amounts=<?php echo json_encode($total_amounts) ?>;
total_from_others=<?php echo json_encode($total_from_others); ?>;

total_amount=0;
for (var key in total_from_others)
{
	var is_negative=false;
	if (total_from_others[key] < 0)
	{
		is_negative = true;
	}
	var needed_id="[id='total-"+key+"']";
	if (is_negative)
	{
		$(needed_id).addClass('charge');
		$(needed_id).text("$"+Number(total_from_others[key]*-1).toFixed(2));
	}
	else
	{
		$(needed_id).addClass('payment');
		$(needed_id).text("$"+Number(total_from_others[key]).toFixed(2));
	}
	total_amount=total_amount+total_from_others[key];
}

console.log("total_amount:");
console.log(total_amount);

if (total_amount > 0)
{
	$("[id='total-prefix']").prepend(' People owe me a total of ');
	$("[id='total-amount']").append(Number(total_amount).toFixed(2));
	$("[id='total-amount']").addClass('payment');
	$("[id='total-prefix']").append('.');
}
else
{
	$("[id='total-prefix']").prepend(' I owe people a total of ');
	$("[id='total-amount']").append(Number(total_amount*-1).toFixed(2));
	$("[id='total-amount']").addClass('charge');
	$("[id='total-prefix']").append('.');
}

</script>
