<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
// define('DB_SERVER', 'localhost');
// define('DB_USERNAME', '');
// define('DB_PASSWORD', '');
// define('DB_NAME', 'MIYA_Customers');

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "Sql783knui1-1l;/klaa-9";
$dbName = "mentorship_db";

$conn = mysqli_connect($servername,$dbUsername,$dbPassword,$dbName);

if(!$conn){
    die("Connection Failed: ".mysqli_connect_error());
}

else{
	echo("Registration success");
	echo "<br>"; 	
}