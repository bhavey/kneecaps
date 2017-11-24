<head></head>
<?php
	$conn = include 'mysql_auth.php';

	$sql = "DELETE FROM transaction WHERE id=\"" . $_POST["id"] . "\"";

	$result = $conn->query($sql);

	$conn->close();
?>
