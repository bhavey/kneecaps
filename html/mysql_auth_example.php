<?php
// https://www.w3schools.com/php/php_mysql_select.asp
$servername = "localhost";
$username = "GIT_USERNAME";
$password = "GIT_PASSWORD";
$dbname = "DATABASE_NAME";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

return $conn;
?>

