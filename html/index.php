<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Kneecaps Made Simple</title>
	<link rel="stylesheet" type="text/css" href="style/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
	<div class="main_page">
		<div class="page_header floating_element">
			<img src="/icons/kneecaps.jpg" alt="Gimme my money" class="floating_element"/>
			<span class="floating_element">
				Kneecaps Made Simple
			</span>
		</div>
		<div class="content_section floating_element">
			<form action="/user.php" id="userform">
				Who are you?

				<select name="user">
					<?php
						// Get our MySQL connection.
						$conn = include 'mysql_auth.php';
						$sql = "SELECT * FROM person";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
						    // output data of each row
    						while($row = $result->fetch_assoc()) {
        						echo "<option value=\"" . $row["name"] . "\">" . $row["name"] . "</option>";
    						}
						} else {
						    echo "0 results";
						}
						$conn->close();
					?>
				</select>
				<input type="submit" value="GO">
			</form>
			<br><br><br>
		    Alpha Build 2017
		</div>
	</body>
</html>
<script>



</script>
