<?php
	require_once('backend/static-files/php/functions.inc.php');

	session_start();
	
	redirectIfNotLoggedIn('admin');

	removeAllGraduatedParticipants();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$basePath = "backend/static-files/python";

		if (isset($_POST['delete-match'])) {
			$split = explode('-', $_POST['match-to-delete-input']);
			$mentorStarid = $split[0];
			$menteeStarid = $split[1];

			$sqlQuery = "DELETE FROM mentorship WHERE `mentor starid` = '$mentorStarid' and `mentee starid` = '$menteeStarid'";

			$stmt = connect()->prepare($sqlQuery);
			$stmt->execute();
		}
		else if (isset($_POST['create-matches-manual'])) {
			$startDate = date('Y-m-d', strtotime($_POST['m-start-date']));
			$endDate   = date('Y-m-d', strtotime($_POST['m-end-date']));

			$mentorStarid = $_POST['m-mentor-starid'];
			$menteeStarid = $_POST['m-mentee-starid'];

			shell_exec("python $basePath/create-matches-manual.py $startDate $endDate $mentorStarid $menteeStarid");
		}
		else if (isset($_POST['create-matches-extend'])) {
			$endDate   = date('Y-m-d', strtotime($_POST['e-end-date']));

			$mentorStarid = $_POST['mentor-starid'];
			$menteeStarid = $_POST['mentee-starid'];

			extendMatch($mentorStarid, $menteeStarid, $endDate);
		}
		else if (isset($_POST['create-matches-auto'])) {
			$startDate = date('Y-m-d', strtotime($_POST['a-start-date']));
			$endDate   = date('Y-m-d', strtotime($_POST['a-end-date']));

			shell_exec("python $basePath/create-matches-auto.py $startDate $endDate");
		}

		unset($_POST);

		header("Location: admin-dashboard.php");
		return;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="styles/common.css">
	<link rel="stylesheet" href="styles/admin-dashboard.css">
	<script src="scripts/header-template.js"></script>
	<!-- For some reason, Font Awesome icons don't show up unless we have this in the file that uses them (e.g. admin-dashboard.php). -->
	<script src="https://kit.fontawesome.com/0a01d33e89.js" crossorigin="anonymous"></script>
	<script src="scripts/admin-dashboard-functions.js"></script>
	<style>
		/* #loading-overlay {
			display: block;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: white;
			z-index: 1;
			color: black;
			font-size: 24px;
			text-align: center;
			padding-top: 50vh;
		} */
	</style>
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
	
	displayNavbar($_SESSION);
?>

<!-- <div id="loading-overlay">Loading...</div> -->

<div id="main-container">
<div id="top-container">
	<div class="fb centering top-row-content-container">
		<form id="create-matches-form" method="post" action="admin-dashboard.php">
			<div id="matches-row">
				<div class="btn" id="match-btn-m" id="m">Create Match Manualy</div>
				<div class="btn" id="match-btn-e" id="e">Extend Existing Match</div>
				<div class="btn" id="match-btn-a" id="a">Create Matches Automatically</div>
				<div class="btn" id="view-match-reasons-btn">View Why Two Participants Were Matched</div>
			</div>
		</form>


	</div>
</div>


<div id="bottom-left-container">
	<h2>Participant Info</h2>

	<p id="default-part-info">Select a participant by clicking their StarID in the right pane.</p>

	<div class="scroll-box">
	<?php
		$participantTblColumns = ['starid', 'first name', 'last name', 'graduation date', 'is active', 'is mentor', 'international student', 'lgbtq+', 'student athlete', 'multilingual', 'not born in this country', 'transfer student', 'first generation college student', 'unsure or undecided about major', 'interested in diversity groups', 'misc info'];

		$assocTblColumns = ['major', 'pre program', 'max matches', 'gender', 'second language', 'religious affiliation', 'hobby', 'race', 'important quality'];

		$cols = str_replace(`graduation date`, "DATE_FORMAT('`graduation date`', '%m-%d-%y')`", join("`, `", $participantTblColumns));

		
		$sqlQuery = "SELECT `starid` FROM `participant`";
		$stmt = connect()->prepare($sqlQuery);
		$stmt->execute();

		foreach ($stmt->fetchAll() as $row) {
			$starid = $row['starid'];
			echo "<table id=\"participant-info-$starid\" class=\"participant-info-values-set\">
				<colgroup>
					<col class=\"participant-label\">
					<col class=\"participant-value\">
				</colgroup>
			";
			$sqlQuery = "SELECT `" . $cols . "` FROM `participant` WHERE `starid` = '$starid'";
			$stmt = connect()->prepare($sqlQuery);
			$stmt->execute();
			$res = $stmt->fetchAll()[0];
			
			if (count($res) > 0) {
				foreach ($res as $colName => $val) {
					if ($val == "") {
						$val = "None or N/A";
					}
					if (!str_contains($val, '-')) {
						$val = str_replace("0", "No", str_replace("1", "Yes", $val));
					}

					echo '<tr>';
					echo "<td>$colName</td>";
					echo "<td>$val</td>";
	
					echo '</tr>';
				}
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

				
				$results = $stmt->fetchAll();
				$valsToEcho = count($results) == 0 ? "None or N/A" : "";
				
				foreach ($results as $res) {
					$valsToEcho .= $res[array_keys($res)[0]] . ", ";
				}
				
				if (count($results) != 0) {
					$valsToEcho = substr($valsToEcho, 0, strlen($valsToEcho) - 2);
				}
				echo "<tr>";
				echo "<td>$colName</td>";
				echo "<td>$valsToEcho</td>";
				echo "</tr>";
			}
			echo '</table>';
		}

	?>
	</div>

</div>

<div id="bottom-right-container">
	<h2>Participants List</h2>

	<div id="participant-filters">
		<div id="flipswitch">
			<div id="vt">
				<div id="view-mentees" class="btn">View Only Mentees</div>
				<div id="view-mentors" class="btn">View Only Mentors</div>
			</div>
		</div>

		<div id="participant-search-boxes">
			<input 
				id="starid-search-box"
				class="text-input participant-search-text-input"
				type="text"
				maxlength="8"
				placeholder="Enter StarID"
				onkeyup="updateResultsByStarid()">

			<input 
				id="name-search-box"
				class="text-input participant-search-text-input"
				type="text"
				placeholder="Enter name"
				onkeyup="updateResultsByName()">
		</div>
	</div>

	<div id="participant-search-container">
		<span id="participant-search-container-header">
			<span></span>
			<span class="search-results-header-item">Active</span>
			<span class="search-results-header-item" id="max-matches-header">Max Matches</span>
			<span class="search-results-header-item" id="group-desc">Mentor StarID</span>
			<span class="search-results-header-item">Match StarIDs</span>
		</span>

		<div class="scroll-box">
		<?php
			foreach ($allParticipants as $p) {
				echoMatchesRow($p);
			}
		?>
		</div>
	</div>

</div>

<div id="confirmation-dialogs">
	<!-- manual match confirmation -->
	<div hidden class="mc" id="mmc">
		<div class="mc-inner horz-centered-content">
			<div class="mc-grid">
				<p class="mc-desc">Manually add match via starids</p>
				<p id="m-cannot-match" class="invalid-input">Cannot create match between these participants due to one or both participants being inactive or already haveing their maximum number of matches.</p>

				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="m-mentor-starid" id="m-mentor-starid" placeholder="Mentor StarID">
				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="m-mentee-starid" id="m-mentee-starid" placeholder="Mentee StarID">

				<p id="m-invalid-mentor-starid" class="invalid-input">Invalid mentor starid.</p>
				<p id="m-invalid-mentee-starid" class="invalid-input">Invalid mentee starid.</p>

				<div class="date-label" id="m-start-date-label">Start Date:</div>
				<div class="date-label" id="m-end-date-label">End Date:</div>

				<input required default="" form="create-matches-form" class="date-input" type="date" name="m-start-date" id="m-start-date">
				<input required default="" form="create-matches-form" class="date-input" type="date" name="m-end-date" id="m-end-date">

				<p id="m-invalid-start-end-dates" class="invalid-date invalid-input">Start date must be before end date.</p>

				<div class="dialog-btns">
					<button id="m-btn-cancel" class="btn-cancel btn" onclick="hideAllDialogBoxes()">Cancel</button>
					<button disabled id="m-btn-confirm" class="btn-confirm btn" onsubmit="hideAllDialogBoxes()" name="create-matches-manual" value="1" type="submit" form="create-matches-form">Create Match</button>
				</div>
			</div>
		</div>
	</div>

	<!-- extend matches confirmation -->
	<div hidden class="mc" id="emc">
		<div class="mc-inner horz-centered-content">
			<div class="mc-grid">
				<p class="mc-desc">Extend an existing match where both participants are still at WSU after the end date</p>

				<p id="e-no-match-exists" class="invalid-input">No match exists between these participants.</p>
				<p id="invalid-match-to-extend" class="invalid-input">This match cannot be extended.</p>

				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="mentor-starid" id="e-mentor-starid" placeholder="Mentor StarID">
				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="mentee-starid" id="e-mentee-starid" placeholder="Mentee StarID">

				<p id="e-invalid-mentor-starid" class="invalid-input">Invalid mentor starid.</p>
				<p id="e-invalid-mentee-starid" class="invalid-input">Invalid mentee starid.</p>

				<div class="date-label" id="e-end-date-label">Extend to:</div>
				
				<input required default="" form="create-matches-form" class="date-input centered-input" type="date" name="e-end-date" id="e-end-date">
				<p id="e-invalid-end-date1" class="invalid-date invalid-input">Date must be on or before the earlier of the two graduation dates.</p>
				<p id="e-invalid-end-date2" class="invalid-date invalid-input">Date must be after the current end date of the match.</p>
				
				<div class="dialog-btns">
					<button id="e-btn-cancel" class="btn-cancel btn" onclick="hideAllDialogBoxes()">Cancel</button>
					<button disabled id="e-btn-confirm" class="btn-confirm btn" onsubmit="hideAllDialogBoxes()" name="create-matches-extend" value="1" type="submit" form="create-matches-form">Extend Match</button>
				</div>
			</div>
		</div>
	</div>

	<!-- automatic matches confirmation -->
	<div hidden class="mc" id="amc">
		<div class="mc-inner horz-centered-content">
			<div class="mc-grid">
				<p class="mc-desc">Automatically match as many available participants as possible according to the important qualities they selected.</p>

				<div class="date-label" id="a-start-date-label">Start Date:</div>
				<div class="date-label" id="a-end-date-label">End Date:</div>

				<input required default="" form="create-matches-form" class="date-input" type="date" name="start-date" id="a-start-date">
				<input required default="" form="create-matches-form" class="date-input" type="date" name="end-date" id="a-end-date">

				<p id="a-invalid-start-end-dates" class="invalid-date invalid-input">Start date must be before end date.</p>

				<div class="dialog-btns">
					<button id="a-btn-cancel" class="btn-cancel btn" onclick="hideAllDialogBoxes()">Cancel</button>
					<button id="a-btn-confirm" class="btn-confirm btn" onsubmit="hideAllDialogBoxes()" name="create-matches-auto" value="1" type="submit" form="create-matches-form">Create Matches</button>
				</div>
			</div>
		</div>
	</div>

	<!-- delete match confirmation -->
	<div hidden class="dmc" id="dmc">
		<div class="horz-centered-content">
			<p>Select the participant to unmatch.</p>

			<div id="dmc-matches">
				<?php
					$participants = getAllParticipantStarids();

					foreach ($participants as $starid) {
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

			<div class="dialog-btns">
				<div id="dmc-show-after-select">
					<p id="dmc-show-after-select-message"></p>

					<form id="delete-matches-form" method="post">
						<button name="delete-match" onsubmit="showDeleteMatchesConfirmation()" class="btn-confirm btn btn-danger" id="dmc-confirm">Delete match</button>
						<input type="text" name="match-to-delete-input" id="match-to-delete-input">
					</form>
				</div>

				<button id="dmc-btn-cancel" onclick="hideDeleteMatchesConfirmation()" class="btn-cancel btn btn-outline">Cancel</button>
			</div>
		</div>
	</div>

	<div hidden id="match-reasons" class="mc">
		<!-- mr = match reasons -->
		<!-- mc = match confirmation (even though the user doesn't create matches in this dialog box) -->
		<div class="mc-inner horz-centered-content">
			<div class="mc-grid">
				<p class="mc-desc">View the factors that led to the algorithm matching two participants.</p>

				<!-- "r" as a prefix means "reason" -->
				<p id="r-no-match-exists" class="invalid-input">No match exists between these participants.</p>
				<input required default="" maxlength="8" class="text-input" type="text" name="r-mentor-starid" id="r-mentor-starid" placeholder="Mentor StarID">
				<input required default="" maxlength="8" class="text-input" type="text" name="r-mentee-starid" id="r-mentee-starid" placeholder="Mentee StarID">

				<p id="r-invalid-mentor-starid" class="invalid-input">Invalid mentor starid.</p>
				<p id="r-invalid-mentee-starid" class="invalid-input">Invalid mentee starid.</p>

				<div class="dialog-btns">
					<button id="r-btn-cancel" class="btn-cancel btn" onclick="hideAllDialogBoxes()">Cancel</button>
					<button disabled id="r-btn-confirm" class="btn-confirm btn" name="create-matches-manual" value="1" type="submit">View Reasons</button>
				</div>

				<tr>
					<?php
						foreach ($allMatches as $match) {
							$mentorStarid = $match['mentor starid'];
							$menteeStarid = $match['mentee starid'];

							$mentorQuals = getParticipantInfo($mentorStarid, ['important quality'])['important quality'];
							$menteeQuals = getParticipantInfo($menteeStarid, ['important quality'])['important quality'];

							echo "<table class=\"match-reasons-tbl\" id=\"$mentorStarid-$menteeStarid\">";
							echo "
								<tr>
								<th>Important quality $mentorStarid selected</th>
								<th>$mentorStarid's value(s) for this quality</th>
								<th>$menteeStarid's value(s) for this quality</th>
								<th class=\"match-reasons-col-gap\"></th>
								<th>Important quality $menteeStarid selected</th>
								<th>$menteeStarid's value(s) for this quality</th>
								<th>$mentorStarid's value(s) for this quality</th>
								</tr>
							";
							for ($i = 0; $i < 3; $i++) {
								$mentorQual = $mentorQuals[$i];
								$menteeQual = $menteeQuals[$i];

								$mentorValStr = formatParticipantInfo(getParticipantInfo($mentorStarid, [$menteeQual])[$menteeQual]);
								$menteeValStr = formatParticipantInfo(getParticipantInfo($menteeStarid, [$mentorQual])[$mentorQual]);

								$mentorSelfVal = formatParticipantInfo(getParticipantInfo($mentorStarid, [$mentorQual])[$mentorQual]);
								$menteeSelfVal = formatParticipantInfo(getParticipantInfo($menteeStarid, [$menteeQual])[$menteeQual]);

								echo "
									<tr>
										<td>$mentorQual</td>
										<td>$mentorSelfVal</td>
										<td>$menteeValStr</td>
										<td class=\"match-reasons-col-gap\"></td>
										<td>$menteeQual</td>
										<td>$menteeSelfVal</td>
										<td>$mentorValStr</td>
									</tr>
								";
							}
						}
						?>
						</tr>
					</table>
			</div>
		</div>
	</div>
</div>



<script>
	viewMentorsBtn.addEventListener("click", viewMentors);
	viewMenteesBtn.addEventListener("click", viewMentees);
	document.getElementById('starid-search-box').addEventListener('focus', clearSearchBoxes);
	document.getElementById('name-search-box').addEventListener('focus', clearSearchBoxes);

	document.getElementById('m-mentor-starid').addEventListener('input', () => {validateManual('mentor')}); // mentor starid
	document.getElementById('m-mentee-starid').addEventListener('input', () => {validateManual('mentee')}); // mentee starid
	document.getElementById('m-mentor-starid').addEventListener('blur', () => {validateManual('mentor')});
	document.getElementById('m-mentee-starid').addEventListener('blur', () => {validateManual('mentee')});
	document.getElementById('m-start-date').addEventListener('input', () => {validateManual('start date')});
	document.getElementById('m-end-date').addEventListener('input', () => {validateManual('end date')});

	document.getElementById('e-mentor-starid').addEventListener('input', () => {validateExtend('mentor')});
	document.getElementById('e-mentee-starid').addEventListener('input', () => {validateExtend('mentee')});
	document.getElementById('e-mentor-starid').addEventListener('blur', () => {validateExtend('mentor')});
	document.getElementById('e-mentee-starid').addEventListener('blur', () => {validateExtend('mentee')});
	document.getElementById('e-end-date').addEventListener('input', () => {validateExtend('end date')});
	document.getElementById('e-end-date').addEventListener('blur', () => {validateExtend('end date')});

	document.getElementById('a-start-date').addEventListener('input', () => {validateAuto('start date')});
	document.getElementById('a-end-date').addEventListener('input', () => {validateAuto('end date')});
	document.getElementById('a-start-date').addEventListener('blur', () => {validateAuto('start date')});
	document.getElementById('a-end-date').addEventListener('blur', () => {validateAuto('end date')});

	document.getElementById('r-mentor-starid').addEventListener('input', () => {validateViewMatchReasons('mentor')});
	document.getElementById('r-mentee-starid').addEventListener('input', () => {validateViewMatchReasons('mentee')});
	document.getElementById('r-mentor-starid').addEventListener('blur', () => {validateViewMatchReasons('mentor')});
	document.getElementById('r-mentee-starid').addEventListener('blur', () => {validateViewMatchReasons('mentee')});
	document.getElementById('r-btn-confirm').addEventListener('click', () => {
		selectedMatch = document.getElementById('r-mentor-starid').value + '-' + document.getElementById('r-mentee-starid').value;
		hideAllMatchReasonsExceptSelected();
	});

	document.getElementById("match-btn-m").addEventListener("click", () => {
		setSelectedMatchMode('m');
		showCreateMatchesConfirmation();
	});

	document.getElementById("match-btn-e").addEventListener("click", () => {
		setSelectedMatchMode('e');
		showCreateMatchesConfirmation();
	});

	document.getElementById("match-btn-a").addEventListener("click", () => {
		setSelectedMatchMode('a');
		showCreateMatchesConfirmation();
	});

	document.getElementById("view-match-reasons-btn").addEventListener("click", () => {
		setSelectedMatchMode('r');
		showMatchReasons();
	});

	// partBtn.addEventListener("mouseout", mouseoutPart);
	// partBtn.addEventListener("click", selectPart);
	// partBtn.addEventListener("mouseover", mouseoverPart);

	// adminBtn.addEventListener("mouseout", mouseoutAdmin);
	// adminBtn.addEventListener("click", selectAdmin);
	// adminBtn.addEventListener("mouseover", mouseoverAdmin);

	setSelectedMatchMode("m");
	hideDeleteMatchesConfirmation();

	setSelectedMatchMode("a");
	hideDeleteMatchesConfirmation();

	setSelectedMatchMode("m");
	hideDeleteMatchesConfirmation();

	hideAllMatchReasonsExceptSelected();

	viewMentees();
	updateButtonHighlighting("match-type-btn");
	// orderParticipantInfo();

	setSelectedStarid('');

	// function hideLoadingOverlay() {
	// 	console.log('h');
	// 	const loadingOverlay = document.querySelector('#loading-overlay');
	// 	loadingOverlay.style.display = 'none';
	// }
	// window.addEventListener('load', hideLoadingOverlay);

	document.getElementById('default-part-info').hidden = false;
</script>

</body>
</html>
