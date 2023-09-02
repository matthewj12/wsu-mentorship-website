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
	$importantQualities = getParticipantInfo($starid, ['important quality'])['important quality'];
	$matchInfo = [];
	foreach ($matchStarids as $sid) {
		$matchInfo[$sid] = getParticipantInfo($sid, ['first name', 'last name']);
	}
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

	<div id="left-and-right-halves">
	<div id="left-half">
		<div class="stuff">
			<h2>Your Information</h2>
			<table id="left-tbl">
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

			<a href="survey.php"><div class="btn" id="survey-btn">Start Survey</div></a>

			<p id="retake-survey-note">Retaking the survey after you have already completed it will overwrite the information you entered in your previous submission.</p>
		</div>
	</div>

	<div id="right-half">
		<div class="stuff">
			<h2>Your <?php echo $groupOpposite ?></h2>

			<?php
			if (count($matchStarids) == 0) {
				echo "<p id=\"no-matches\">You are not currently matched with any {$groupOpposite}.";
			}
			else {
				echo '
				<table id="right-tbl">
					<colgroup>
						<col class="col1">
						<col class="col2">
						<col class="col3">
						<col class="col4">
					</colgroup>
					<tr>
						<th class="name-col">Name</th>
						<th>Email Address</th>
						<th class="match-qualities-col">Info (their values for your important qualities)</th>
						<th class="misc-info-col">Notes</th>
					</tr>';

						$qualitiesInParticipantTbl = ['lgbtq+', 'not born in this country', 'multilingual', 'interested in diversity groups', 'first generation college student', 'international student', 'student athlete', 'transfer student'];
					
						foreach ($matchInfo as $sid => $mi) {
							echo '<tr>';
							$mFName = $mi['first name'][0];
							$mLName = $mi['last name'][0];
							echo "<td class=\"name-col\">$mFName $mLName</td>";
							echo "<td>$sid@go.minnstate.edu</td>";

							$matchQualities = "";
							foreach ($importantQualities as $q) {
								if ($q == 'unused') {
									continue;
								}
								if (in_array($q, $qualitiesInParticipantTbl)) {
									$val = getParticipantInfo($sid, [$q])[$q][0];
									$matchQualities .= "{$q}: {$val}";
								}
								else {
									$vals = getParticipantInfo($sid, [$q])[$q];
									$matchQualities .= "<b>{$q}:</b> " . implode(', ', $vals);
								}

								if (array_search($q, $importantQualities) != 2) {
									$matchQualities .= '<br>';
								}
							}

							$miscInfo = getParticipantInfo($sid, ['misc info'])['misc info'][0];

							echo "<td class=\"match-qualities-col\">" . $matchQualities . "</td>";
							echo "<td class=\"misc-info-col\">" . $miscInfo . "</td>";
							echo '</tr>';
						}
				echo '</table>';
			}
			?>

		</div>
	</div>
	</div>

</body>
</html>