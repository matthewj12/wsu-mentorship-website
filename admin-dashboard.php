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
</head>
<body>

<?php
	$allParticipants = getAllParticipantsAsObjs();
	displayNavbar($_SESSION);
?>

<div id="top-container">
	<h1>Administrator Dashboard</h1>

	<div class="fb centering top-row-content-container">
		<form id="create-matches-form" method="post" action="admin-dashboard.php">
			<div id="matches-row">
				<div class="btn" id="match-btn-m" onclick="setSelectedMatchMode('m');showCreateMatchesConfirmation();" id="m">Create Match Manualy</div>
				<div class="btn" id="match-btn-e" onclick="setSelectedMatchMode('e');showCreateMatchesConfirmation();" id="e">Extend Existing Match</div>
				<div class="btn" id="match-btn-a" onclick="setSelectedMatchMode('a');showCreateMatchesConfirmation();" id="a">Create Matches Automatically</div>
			</div>
		</form>


	</div>
</div>


<div id="bottom-left-container">
	<h2>Participant Info</h2>

	<p id="default-part-info">Select a participant by clicking their StarID in the right pane.</p>

	<?php
		$participantTblColumns = ['starid', 'first name', 'last name', 'graduation date', 'is active', 'is mentor', 'international student', 'lgbtq+', 'student athlete', 'multilingual', 'not born in this country', 'transfer student', 'first generation college student', 'unsure or undecided about major', 'interested in diversity groups', 'misc info'];

		$assocTblColumns = ['major', 'pre program', 'max matches', 'gender', 'second language', 'religious affiliation', 'hobby', 'race', 'important quality'];

		$cols = str_replace(`graduation date`, "DATE_FORMAT('`graduation date`', '%m-%d-%y')`", join("`, `", $participantTblColumns));

		
		$sqlQuery = "SELECT `starid` FROM `participant`";
		$stmt = connect()->prepare($sqlQuery);
		$stmt->execute();

		foreach ($stmt->fetchAll() as $row) {
			$starid = $row['starid'];
			echo "<table id=\"participant-info-$starid\" class=\"participant-info-values-set\">";
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
					echo "<th>$colName</th>";
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
				echo "<th>$colName</th>";
				echo "<td>$valsToEcho</td>";
				echo "</tr>";
			}
			echo '</table>';
		}

	?>

</div>

<div id="bottom-right-container">
	<h2>Participants List</h2>

	<div id="participant-filters">
		<!-- <div class="btn" id="matches-btn">Edit Matches</div> -->

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

		<?php
			foreach ($allParticipants as $p) {
				echoMatchesRow($p);
			}
		?>
	</div>
</div>

<div class="confirmation-dialogs">
	<!-- manual match confirmation -->
	<div hidden class="mc" id="mmc">
		<div class="mc-inner horz-centered-content">
			<div class="mc-grid">
				<p class="mc-desc">Manually add match via starids</p>

				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="m-mentor-starid" id="m-mentor-starid" placeholder="Mentor StarID" onfocusout="validateManual('mentor')">
				<input required default="" maxlength="8" form="create-matches-form" class="text-input" type="text" name="m-mentee-starid" id="m-mentee-starid" placeholder="Mentee StarID" onfocusout="validateManual('mentee')">

				<p id="m-invalid-mentor-starid" class="invalid-input">Invalid mentor starid.</p>
				<p id="m-invalid-mentee-starid" class="invalid-input">Invalid mentee starid.</p>

				<div class="date-label" id="m-start-date-label">Start Date:</div>
				<div class="date-label" id="m-end-date-label">End Date:</div>

				<input required default="" form="create-matches-form" class="date-input" type="date" name="m-start-date" id="m-start-date" onfocusout="validateManual('start date')">
				<input required default="" form="create-matches-form" class="date-input" type="date" name="m-end-date" id="m-end-date" onfocusout="validateManual('end date')">

				<p id="m-invalid-start-end-dates" class="invalid-date invalid-input">Start date must be before end date.</p>

				<button id="m-btn-cancel" class="btn-cancel btn" onclick="hideCreateMatchesConfirmation()">Cancel</button>
				<button disabled id="m-btn-confirm" class="btn-confirm btn" onsubmit="hideCreateMatchesConfirmation()" name="create-matches-manual" value="1" type="submit" form="create-matches-form">Create Match</button>
				</div>
			</div>
		</div>
	</div>

	<!-- extend matches confirmation -->
	<div hidden class="mc" id="emc">
		<div class="mc-inner horz-centered-content">
			<div class="mc-grid">
				<p class="mc-desc">Extend an existing match where both participants are still at WSU after the end date</p>

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
	<div hidden class="mc" id="amc">
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


<script src="scripts/admin-dashboard-functions.js"></script>

<script type="module">
	viewMenteesBtn.addEventListener("click", viewMentees);
	viewMentorsBtn.addEventListener("click", viewMentors);
	
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

	viewMentees();
	updateButtonHighlighting("match-type-btn");
	// orderParticipantInfo();
	
	let infoDivs = document.getElementsByClassName('participant-info-values-set');
	for (let i = 0; i < infoDivs.length; i++) {
		infoDivs[i].style.display = 'none';
	}
</script>

</body>
</html>
