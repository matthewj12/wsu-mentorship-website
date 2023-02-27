<?php

	require_once('backend/static-files/php/functions.inc.php');

	session_start();
	redirectIfNotLoggedIn('participant');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script src="scripts/header-template.js"></script>
	
	<link rel="stylesheet" href="styles/participant-dashboard.css">
</head>
<body>
	<?php
		displayNavbar($_SESSION);
	?>
		
	<h1>Participant Dashboard</h1>

	<div id="part-dashboard-box">
		<a href="survey.php">
		<div class="btn" id="survey-btn">
			Start Survey
		</div>
		</a>
	</div>
	

</body>
</html>