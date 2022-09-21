<?php

require 'backend\static-files\php\functions.inc.php';

session_start();

print_r($_SESSION);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Participant Dashboard</h1>
		<br>
		<br>
    <?php
			if (isset($_SESSION['participant-logged-in'])) {
				$sqlQuery = "SELECT COUNT(*) as 'rows' FROM `participant` WHERE `starid` = '" . $_SESSION['participant-dashboard'] . "';";
				$stmt = connect()->prepare($sqlQuery);
				$stmt->execute();
				$res = $stmt->fetchAll()[0]['rows'];

				echo "<br>";

				if ($res == '1') {
					echo '<p>You have already completed the survey.</p>';
				}
				else {
					echo '<p>You have not completed the survey yet.</p>';
				}

				echo "<br>";

				echo '<p><a href="survey.php">Start survey.</a> If you\'ve already completed the survey before, your information will be updated.</p>';

				$isMentor = getParticipantInfo($_SESSION['participant-dashboard'], ['is mentor'])['is mentor'][0];
				$matches = getStaridsOfMatches($_SESSION['participant-dashboard'], $isMentor);

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
				echo '<a href="login.php">Log in</a>';
			}
    ?>
</body>
</html>