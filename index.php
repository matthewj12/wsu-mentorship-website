<?php
	session_start();
	require_once('backend/static-files/php/functions.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script src="scripts/header-template.js"></script>

	<link rel="stylesheet" href="styles/index.css">
<head>
<body>
	<?php
		displayNavbar($_SESSION);
	?>

	<h1>Welcome!</h1>
	<p id="instructions">Select "Login" to begin.</p>
</body>
</html>
