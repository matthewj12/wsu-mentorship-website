<?php

	require_once('backend/static-files/php/functions.inc.php');

	session_start();

	redirectIfNotLoggedIn('participant');

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script src="scripts/header-template.js"></script>
</head>
<body>
	<div class="navbar">
		<a href="index.php">Home</a>
	</div>
	
		<h1>Participant Dashboard</h1>

		<?php
			$sqlQuery = "SELECT COUNT(*) as 'rows' FROM `participant` WHERE `starid` = '" . $_SESSION['participant-starid'] . "';";
			$stmt = connect()->prepare($sqlQuery);
			$stmt->execute();
			$res = $stmt->fetchAll()[0]['rows'];

			if ($res == '1') {
				echo '<p>You have already completed the survey.</p>';
			}
			else {
				echo '<p>You have not completed the survey yet.</p>';
			}


			if (participantExistsInDb($_SESSION['participant-starid'])) {
				echo '<p><a href="survey.php">Retake survey</a>';

				$isMentor = getParticipantInfo($_SESSION['participant-starid'], ['is mentor'])['is mentor'][0];
				$matches = getStaridsOfMatches($_SESSION['participant-starid'], $isMentor);

				$temp = getGroupStr($isMentor);
				$group = $temp[0];
				$groupOpposite = $temp[1];

				echo "<br>Your $groupOpposite" . 's:<br>';

				foreach ($matches as $matchStarid) {
					$firstAndLast = getParticipantInfo($matchStarid, ['first name', 'last name']);
					$first = $firstAndLast['first name'][0];
					$last = $firstAndLast['last name'][0];

					echo "<p>$first $last | $matchStarid@go.minnstate.edu</p>";
				}
			}
			else {
				echo '<p><a href="survey.php">Take survey</a>';
			}
		?>
</body>
</html>