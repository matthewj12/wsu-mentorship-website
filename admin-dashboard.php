<?php
require_once('backend/static-files/php/functions.inc.php');

// Check if the user is logged in, if not then redirect him to landing paget
if (!isset($_SESSION['logged in']) || $_SESSION['logged in'] != true) {
	header("location:../login.php?error=notloggedIn");
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>
	<link rel="stylesheet" href="styles/admin-dashboard.css">
	<script src="scripts/header-template.js"></script>
	<script src="scripts/functions.js"></script>

	<head>

	<body>
		<div class="main-container">
			<form action="backend/static-files/php/run-main.php">
				<input type="submit" value="Create Matches" id="create-matches-btn">
			</form>

			<div id="search-container">
				<input id="search-box" type="text" maxlength="8" placeholder="Enter starid" onkeyup="updateResults()">

				<ul id="search-results-ul">
					<?php

					$sqlQuery = 'SELECT `starid`, `is mentor` FROM `participant`;';

					$stmt = connect()->prepare($sqlQuery);
					$stmt->execute();

					foreach ($stmt->fetchAll() as $row) {
						$starid = $row[array_keys($row)[0]];
						$isMentor = $row[array_keys($row)[1]];

						if ($isMentor == '1') {
							echo "<li class=\"mentor-li\"><a>Mentor | $starid</a></li>";
						} else {
							echo "<li class=\"mentee-li\"><a>Mentee | $starid</a></li>";
						}
					}

					?>
				</ul>
			</div>
		</div>
	</body>

</html>