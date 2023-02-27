<?php

	require_once('backend/static-files/php/functions.inc.php');

	session_start();
	redirectIfNotLoggedIn('participant');
	
	$starid = $_SESSION['participant-starid'];

	$isMentor = isMentor($starid); // boolean
	$temp = getGroupStr($isMentor);
	$group = $temp[0]; // string
	$groupOpposite = $temp[1] . 's'; // strring
	// the [0] array index is needed because some calls to getParticipantInfo return an array containing multiple values.
	$fName = getParticipantInfo($starid, ['first name'])['first name'][0]; // string
	$lName = getParticipantInfo($starid, ['last name'])['last name'][0]; // string
	$surveyCompleted = participantExistsInDb($starid); // boolean
	$matchStarids = getStaridsOfMatches($starid, $isMentor);
	$matchInfo = [];

	foreach ($matchStarids as $sid) {
		$matchInfo[$sid] = getParticipantInfo($sid, ['first name', 'last name']);
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script src="scripts/header-template.js"></script>

	<link rel="stylesheet" href="styles/common.css">
	<link rel="stylesheet" href="styles/participant-dashboard.css">
</head>
<body>
	<?php
		displayNavbar($_SESSION);
	?>
		
	<h1>Participant Dashboard</h1>

	<div id="left-half">
		<div class="stuff">
			<h2>Your Information</h2>
			<table>
				<tr>
					<th style="text-align: left">Name</th>
					<td><?php
						echo $fName . ' ' . $lName;
					?></td>
				</tr>
				<tr>
					<th style="text-align: left">Role</th>
					<td><?php
						echo $group;
					?></td>
				</tr>
				<tr>
					<th style="text-align: left">Survey Status</th>
					<td>Complete</td>
				</tr>
			</table>

			<a href="survey.php"><div class="btn" id="survey-btn">Retake Survey</div></a>

			<p id="retake-survey-note">Retaking the survey after you have already completed it will overwrite the information you entered in your previous submission.</p>
		</div>
	</div>

	<div id="right-half">
		<div class="stuff">
			<h2>Your <?php echo $groupOpposite ?></h2>

			<table>
				<tr>
					<th>Name</th>
					<th>Email Address</th>
				</tr>
				<?php
					foreach ($matchInfo as $sid => $mi) {
						echo '<tr>';
						$mFName = $mi['first name'];
						$mLName = $mi['last name'];
						echo "<td>$mFname $mLName</td>";
						echo "<td>$sid@go.minnstate.edu</td>";
						echo '</tr>';
					}
				?>
			</table>

			<p>Stuff you have in common: Major, race.</p>

			<p>Notes about [selected participant]: I'm looking forward to this, I'm kinda shy, don't ask me about xyz. Go vikings!!!!</p>
		</div>
	</div>

</body>
</html>