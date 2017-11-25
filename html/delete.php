<head></head>
<?php
	$conn = include 'mysql_auth.php';

	$sql = "UPDATE transaction SET isActive=0 WHERE id=\"" . $_POST["id"] . "\"";

	$result = $conn->query($sql);

	$conn->close();
?>
