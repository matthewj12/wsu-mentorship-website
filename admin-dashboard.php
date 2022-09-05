<?php
	require_once('backend/static-files/php/functions.inc.php');

	if (!isset($_SESSION)) {
		session_start();
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// $_SESSION['postdata'] = $_POST;
		$basePath = "backend/static-files/python";

		echo "<div style=\"position: absolute; bottom: 10px; left: 10px\">";

		if (isset($_POST['delete-match'])) {
			$split = explode('-', $_POST['match-to-delete-input']);
			$mentorStarid = $split[0];
			$menteeStarid = $split[1];

			$sqlQuery = "DELETE FROM mentorship WHERE `mentor starid` = '$mentorStarid' and `mentee starid` = '$menteeStarid'";

			$stmt = connect()->prepare($sqlQuery);
			$stmt->execute();
		}
		if (isset($_POST['create-matches-manual'])) {
			$startDate = date('Y-m-d', strtotime($_POST['m-start-date']));
			$endDate   = date('Y-m-d', strtotime($_POST['m-end-date']));

			$mentorStarid = $_POST['mentor-starid'];
			$menteeStarid = $_POST['mentee-starid'];

			echo shell_exec("py $basePath/create-matches-manual.py $startDate $endDate $mentorStarid $menteeStarid");
		}
		if (isset($_POST['create-matches-extend'])) {
			$endDate   = date('Y-m-d', strtotime($_POST['e-end-date']));

			$mentorStarid = $_POST['mentor-starid'];
			$menteeStarid = $_POST['mentee-starid'];

			extendMatch($mentorStarid, $menteeStarid, $endDate);
		}
		if (isset($_POST['create-matches-auto'])) {
			$startDate = date('Y-m-d', strtotime($_POST['a-start-date']));
			$endDate   = date('Y-m-d', strtotime($_POST['a-end-date']));

			echo shell_exec("py $basePath/create-matches-auto.py $startDate $endDate");
		}

		echo "</div>";
		unset($_POST);

		header("Location: admin-dashboard.php");
		return;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="styles/admin-dashboard.css">
	<script src="scripts/header-template.js"></script>
	<script src="scripts/admin-dashboard-functions.js"></script>
	<!-- For some reason, Font Awesome icons don't show up unless we have this in the file that uses them. -->
	<script src="https://kit.fontawesome.com/0a01d33e89.js" crossorigin="anonymous"></script>
<head>
<body>

<?php
	$allParticipants = getAllParticipantsAsObjs();
	$allMatches = [];

	$sqlQuery = 'SELECT * FROM `mentorship`;';
	$stmt = connect()->prepare($sqlQuery);
	$stmt->execute();
	foreach ($stmt->fetchAll() as $row) {
		$allMatches[] = $row;
	}

	echo "<div hidden id='invisible-matches-elem'>";
	echoMatchesAsJson($allMatches);
	echo '</div>';
?>

<div class="main-container">

<div id="top-left-container">
	<div class="fb centering top-row-content-container">
		<form id="create-matches-form" method="post" action="admin-dashboard.php">
			<div style="display: flex;">
				<span>
					<div class="top-row-1">
						Matching type:
						<button type="button" class="match-type-btn" onclick="setSelectedMatchMode('m')" id="m">Manual</button>
						<button type="button" class="match-type-btn" onclick="setSelectedMatchMode('e')" id="e">Extend matches</button>
						<button type="button" class="match-type-btn" onclick="setSelectedMatchMode('a')" id="a">Automatic</button>
					</div>

					<div class="top-row-2">
						<button class="btn btn-primary" type="button" onclick="showCreateMatchesConfirmation()" id="create-matches-btn">Create Matches</button>
					</div>
				</span>
			</div>
		</form>
	</div>
</div>

<div id="top-right-container">
	<div class="fb centering top-row-content-container">
		<div>
			<div class="top-row-1">
				<button id="view-mentees" class="btn btn-info" onclick="setMentorsSelected(false)">View Mentees</button>
				<button id="view-mentors" class="btn btn-primary" onclick="setMentorsSelected(true);">View Mentors</button>
			</div>
			<div class="top-row-2">
				<input 
					id="starid-search-box"
					class="text-input participant-search-text-input"
					type="text"
					maxlength="8"
					placeholder="Enter Mentee StarID"
					onkeyup="updateResults()"
				>

				<input 
					id="name-search-box"
					class="text-input participant-search-text-input"
					type="text"
					maxlength="8"
					placeholder="Enter Mentee name"
					onkeyup=""
				>
			</div>
		</div>
	</div>
</div>

<div id="bottom-left-container">
	<h1>Participant Info</h1>

	<div id="participant-info-grid">
		<?php
			$participantTblColumns = ['starid', 'first name', 'last name', 'graduation date', 'is active', 'is mentor', 'international student', 'lgbtq+', 'student athlete', 'multilingual', 'not born in this country', 'transfer student', 'first generation college student', 'unsure or undecided about major', 'interested in diversity groups', 'misc info'];

			$assocTblColumns = ['major', 'pre program', 'max matches', 'gender', 'second language', 'religious affiliation', 'hobby', 'race', 'important quality'];
			
			echo "<div style=\"grid-column: 1\">";

			// echo '<div id="participant-info-side-header">';
			$row = 1;
			foreach ($participantTblColumns as $col) {
				$idVal = "participant-info-row-name-" . str_replace(' ', '-', $col);
				echo "<div class=\"participant-info-row-name\" id=\"$idVal\">$col</div>";
				$row++;
			}
			foreach ($assocTblColumns as $col) {
				$idVal = "participant-info-row-name-" . str_replace(' ', '-', $col);
				echo "<div class=\"participant-info-row-name\" id=\"$idVal\">$col</div>";
				$row++;
			}

			echo '</div>';
		?>

		<div style="grid-column: 2" id="participant-info-values">
			<?php
				$sqlQuery = "SELECT `starid` FROM `participant`";
				$stmt = connect()->prepare($sqlQuery);
				$stmt->execute();
				$results = $stmt->fetchAll();
				$sqlQuery = "";

				foreach ($results as $row) {
					$starid = $row['starid'];

					echo "<div hidden style=\"\" id=\"participant-info-$starid\" class=\"participant-info-values-set\">";

					// columns in `participant`
					unset($participantTblColumns['starid']);
					$cols = str_replace(`graduation date`, "DATE_FORMAT('`graduation date`', '%m-%d-%y')`", join("`, `", $participantTblColumns));
					$sqlQuery = "SELECT `" . $cols . "` FROM `participant` WHERE `starid` = '$starid'";

					$stmt = connect()->prepare($sqlQuery);
					$stmt->execute();

					$rowNum = 1;

					foreach ($stmt->fetchAll()[0] as $colName => $val) {
						if ($val == "") {
							$val = "None or N/A";
						}
						if (!str_contains($val, '-')) {
							$val = str_replace("0", "No", str_replace("1", "Yes", $val));
						}

						$classVal = "participant-info-val participant-info-val-" . str_replace(' ', '-', $colName);
						echo "<div class=\"$classVal\">" . $val . "</div>";
						$rowNum++;
					}

					// columns in association tables
					foreach ($assocTblColumns as $colName) {
						$sqlQuery = "
							select `$colName`
							from `$colName assoc tbl` join `$colName ref tbl`
								WHERE
									`$colName assoc tbl`.`starid` = '$starid' and
									`$colName assoc tbl`.`$colName id` = `$colName ref tbl`.`id`";

						$stmt = connect()->prepare($sqlQuery);
						$stmt->execute();

						$classVal = "participant-info-val participant-info-val-" . str_replace(' ', '-', $colName);
						echo "<div class=\"$classVal\" id=\"$idVal\">";

						$results = $stmt->fetchAll();
						$valsToEcho = count($results) == 0 ? "None or N/A" : "";

						foreach ($results as $res) {
							$valsToEcho .= $res[array_keys($res)[0]] . ", ";
						}

						if (count($results) != 0) {
							$valsToEcho = substr($valsToEcho, 0, strlen($valsToEcho) - 2);
						}
						echo $valsToEcho;

						echo "</div>";
						$rowNum++;
					}

					echo "</div>";
				}
			?>
		</div>
	</div>
</div>

<div id="bottom-right-container">
	<div id="participant-search-container">
		<span id="participant-search-container-header">
			<span></span>
			<span class="search-results-header-item">Active</span>
			<span class="search-results-header-item" id="max-matches-header">Max Matches</span>
			<span class="search-results-header-item" id="group-desc">Mentor StarID</span>
			<span class="search-results-header-item">Match StarIDs</span>
		</span>

		<?php
			$participantStarids = [];
			foreach ($allParticipants as $p) {
				$participantStarids[] = $p->dataPoints['starid'];
			}

			foreach ($participantStarids as $participant) {
				$starid = $participant[0];
				echoMatchesRow($starid);
			}
		?>
	</div>
</div>

<div class="confirmation-dialogs">
	<!-- manual matches confirmation -->
	<div hidden class="fb mc" id="mmc">
		<div class="mc-inner horz-centered-content">
			<div class="mc-grid">
				<p class="mc-desc">Manually add match via starids</p>

				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="mentor-starid" id="m-mentor-starid" placeholder="Mentor StarID" onfocusout="validateManual('mentor')">
				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="mentee-starid" id="m-mentee-starid" placeholder="Mentee StarID" onfocusout="validateManual('mentee')">

				<p id="m-invalid-mentor-starid" class="invalid-input">Invalid mentor starid.</p>
				<p id="m-invalid-mentee-starid" class="invalid-input">Invalid mentee starid.</p>

				<div class="date-label" id="m-start-date-label">Start Date:</div>
				<div class="date-label" id="m-end-date-label">End Date:</div>

				<input required default="" form="create-matches-form" class="date-input" type="date" name="start-date" id="m-start-date" onfocusout="validateManual('start date')">
				<input required default="" form="create-matches-form" class="date-input" type="date" name="end-date" id="m-end-date" onfocusout="validateManual('end date')">

				<p id="m-invalid-start-end-dates" class="invalid-date invalid-input">Start date must be before end date.</p>

				<button id="m-btn-cancel" class="btn-cancel btn" onclick="hideCreateMatchesConfirmation()">Cancel</button>
				<button disabled id="m-btn-confirm" class="btn-confirm btn" onsubmit="hideCreateMatchesConfirmation()" name="create-matches-manual" value="1" type="submit" form="create-matches-form">Create Match</button>
				</div>
			</div>
		</div>
	</div>

	<!-- extend matches confirmation -->
	<div hidden class="fb mc" id="emc">
		<div class="mc-inner horz-centered-content">
			<div class="mc-grid">
				<p class="mc-desc">Extend matches where both participants are still at WSU after the current match's end date</p>

				<p id="no-match-exists" class="invalid-input">No match exists between these participants.</p>
				<p id="invalid-match-to-extend" class="invalid-input">This match cannot be extended.</p>

				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="mentor-starid" id="e-mentor-starid" placeholder="Mentor StarID" onfocusout="validateExtend('mentor')">
				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="mentee-starid" id="e-mentee-starid" placeholder="Mentee StarID" onfocusout="validateExtend('mentee')">

				<p id="e-invalid-mentor-starid" class="invalid-input">Invalid mentor starid.</p>
				<p id="e-invalid-mentee-starid" class="invalid-input">Invalid mentee starid.</p>

				<div class="date-label" id="e-end-date-label">Extend to:</div>
				
				<input required default="" form="create-matches-form" class="date-input centered-input" type="date" name="e-end-date" id="e-end-date" onfocusout="validateExtend('date')">
				<p id="e-invalid-end-date" class="invalid-date invalid-input">Date must be on or before the earlier of the two graduation dates.</p>
				
				<button id="e-btn-cancel" class="btn-cancel btn" onclick="hideCreateMatchesConfirmation()">Cancel</button>
				<button disabled id="e-btn-confirm" class="btn-confirm btn" onsubmit="hideCreateMatchesConfirmation()" name="create-matches-extend" value="1" type="submit" form="create-matches-form">Extend Match</button>
			</div>
		</div>
	</div>

	<!-- automatic matches confirmation -->
	<div hidden class="fb mc" id="amc">
		<div class="mc-inner horz-centered-content">
			<div class="mc-grid">
				<p class="mc-desc">Automatically match as many available participants as possible according to the important qualities they selected.</p>

				<div class="date-label" id="a-start-date-label">Start Date:</div>
				<div class="date-label" id="a-end-date-label">End Date:</div>

				<input required default="" form="create-matches-form" class="date-input" type="date" name="start-date" id="a-start-date" onfocusout="validateAuto('start date')">
				<input required default="" form="create-matches-form" class="date-input" type="date" name="end-date" id="a-end-date" onfocusout="validateAuto('end date')">

				<p id="a-invalid-start-end-dates" class="invalid-date invalid-input">Start date must be before end date.</p>

				<button id="a-btn-cancel" class="btn-cancel btn" onclick="hideCreateMatchesConfirmation()">Cancel</button>
				<button id="a-btn-confirm" class="btn-confirm btn" onsubmit="hideCreateMatchesConfirmation()" name="create-matches-auto" value="1" type="submit" form="create-matches-form">Create Matches</button>
			</div>
		</div>
	</div>

	<!-- delete match confirmation -->
	<div hidden class="fb dmc" id="dmc">
		<div class="horz-centered-content">
			<p>Select the participant(s) to unmatch.</p>

			<div id="dmc-matches">
				<?php
					$participants = getAllParticipantStarids();

					foreach ($participants as $participant) {
						$starid = $participant[0];

						echo "<span class=\"dmc-match-set\" id=\"dmc-match-set-$starid\">";

						$matches = getStaridsOfMatches($starid, getParticipantInfo($starid, ['is mentor'])['is mentor'][0]);
						
						if (count($matches) > 0) {
							$isMentor = getParticipantInfo($starid, ['is mentor'])['is mentor'][0];
							$temp = getGroupStr($isMentor);
							$group = $temp[0];
							$groupOpposite = $temp[1];
							
							echo '<span class="match-starids">';
							foreach ($matches as $matchStarid) {
								echo "<span class=\"$groupOpposite-container starid match-starid match-to-delete-starid\" id=\"match-to-delete-$starid-$matchStarid\" onclick=\"setMatchToDelete('$starid', '$matchStarid')\">$matchStarid
								</span>";
							}
							echo '</span>';
						}
						echo '</span>';
					}
				?>
			</div>

			<div id="dmc-show-after-select">
				<p id="dmc-show-after-select-message"></p>

				<form id="delete-matches-form" method="post">
					<button name="delete-match" onsubmit="showDeleteMatchesConfirmation()" class="btn-confirm btn btn-danger" id="dmc-confirm">Delete match</button>
					<input type="text" name="match-to-delete-input" id="match-to-delete-input">
				</form>
				
				<button onclick="hideDeleteMatchesConfirmation()" class="btn-cancel btn btn-outline">Cancel</button>
			</div>
		</div>
	</div>
</div>

<!-- main container -->
</div>

<script>
	setSelectedMatchMode("m");
	setSelectedStarid('');
	setMentorsSelected(false);
	updateButtonHighlighting("match-type-btn");
	orderParticipantInfo();
</script>

</body>
</html>
