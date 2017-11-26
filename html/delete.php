<head></head>
<?php
	$conn = include 'mysql_auth.php';

	$sql = "UPDATE transaction SET is_active=0, deleted_by=" . $_POST["user_id"] . " WHERE id=" . $_POST["id"];
	echo $sql;

	$conn->query($sql);
	echo "post conn";

	$conn->close();
?>
