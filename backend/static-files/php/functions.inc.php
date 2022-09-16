<?php

require_once('classes.inc.php');

function connect() {
	// $serverName = "localhost";
	// $dbUsername = "root";
	// $dbPassword = "";
	// $dbName = "mp";

	$serverName = "localhost";
	$dbUsername = "root";
	$dbPassword = "Sql783knui1-1l;/klaa-9";
	$dbName = "mp";

	try {
		$dsn = 'mysql:host='.$serverName.';dbname='.$dbName;
		$pdo = new PDO($dsn, $dbUsername, $dbPassword);
		//setting fetch mode
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		//setting error mode
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $pdo;
	}
	catch (PDOException $e) {
		echo "Connection Failed:". $e->getMessage();
	}
}

function getParticipantFields() {
	$columns = [];

	$stmt = connect()->prepare("select group_concat(column_name) as '' from information_schema.columns where table_schema = 'mp' and table_name = 'participant';");
	$stmt->execute();
	$fields = $stmt->fetchAll();

	foreach (explode(",", $fields[0]['']) as $field) {
		$columns[] = $field;
	}

	return $columns;
}

function getMentorshipFields() {
	$columns = [];

	$stmt = connect()->prepare("select group_concat(column_name) as '' from information_schema.columns where table_schema = 'mp' and table_name = 'mentorship';");
	$stmt->execute();
	$fields = $stmt->fetchAll();

	foreach (explode(",", $fields[0]['']) as $field) {
		$columns[] = $field;
	}

	return $columns;
}

function insertParticipant() {
	$starid = $_POST['participant_starid'];
	wipeParticipant($starid);
	// to be consistent with the association tables where there can be multiple entries, we want an array inside an array for a single participant
	$userData = ['participant' => [[]]];
	// special column only in `has important quality assoc tbl` needs special handling, -1 signifies that this is not the current table

	foreach ($_POST as $tblAndColName => $valToInsert) {
		if ($tblAndColName != "submit_survey") {
			$valToInsert = preg_replace('/[^a-z,0-9, ,\',.,\-]/', '', $valToInsert);
			
			$split   = explode('_', $tblAndColName);
			// $tblName = str_replace('-', ' ', str_replace([' 1 ', ' 2 ', ' 3 '], '', $split[0]));
			$tblName = str_replace('-', ' ', $split[0]);
			$colName = str_replace('-', ' ', $split[1]);

			$rank = -1;
			if (str_contains($tblName, 'important quality')) {
				$rank = substr($tblName, 18, 1);
			}

			// Change the date format the the one accepted by mysql
			else if (str_contains($colName, 'date')) {
				$split = explode('-', $valToInsert);
				$valToInsert = $split[2] . '-' . $split[0] . '-' . $split[1];
			}

			$tblName = str_replace([' 1', ' 2', ' 3'], '', $tblName);
			$colName = str_replace([' 1', ' 2', ' 3'], '', $colName);

			// create the array for the values of the table with name $name if it doesn't already exist
			if (array_search($tblName, array_keys($userData)) === false) {
				$userData[$tblName] = [];
			}

			if ($tblName == 'participant') {
				// We don't have all the values needed for the entire row
				$userData['participant'][0][$colName] = $valToInsert;
			}
			else {
				// We have all the values needed for the entire row
				if (str_contains($tblName, 'important quality')) {
					$userData[$tblName][] = ['starid' => $starid, $colName => $valToInsert, 'important quality rank' => $rank];
				}
				else {
					$userData[$tblName][] = ['starid' => $starid, $colName => $valToInsert];
				}

			}
		}
	}

	// need to handle participant table first because participant's primary key (starid) is referenced in association tables
	// we can use index 0 because there is only ever one participant to insert for each survey completed

	doInsert('participant', $userData['participant'][0]);
	unset($userData['participant']);

	foreach ($userData as $tblName => $newEntries) {
		foreach ($newEntries as $newEntry) {
			doInsert($tblName, $newEntry);
		}
	}
}
	
function doInsert($tblName, $colsAndVals) {
	$sqlQuery = "INSERT INTO `$tblName`(`";

	foreach (array_keys($colsAndVals) as $colName) {
		$sqlQuery .= $colName . "`, `";
	}

	// remove final backtick + comman + space
	$sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 4);
	$sqlQuery .= "`) VALUES (";

	foreach ($colsAndVals as $val) {
		$sqlQuery .= "'$val', ";
	}

	// remove final comma + space
	$sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 2);
	$sqlQuery .= ');';
	
	// echo "<p style=\"font-family: monospace;\">$sqlQuery</p><br>";

	try {
		$stmt = connect()->prepare($sqlQuery);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p style=\"font-family: monospace; font-size: 22px\">";
		echo $sqlQuery . $e->getMessage();
		echo "</p>";
	}
}

// Wipes participant's entries in `participant` and `[...] assoc tbl` tables
function wipeParticipant($starid) {
	$getTblsToWipeQuery = "SHOW TABLES WHERE tables_in_mp != 'mentorship' AND INSTR(Tables_in_mp, 'ref tbl') = 0;";

	$stmt = connect()->prepare($getTblsToWipeQuery);
	$stmt->execute();

	foreach ($stmt->fetchAll() as $row) {
		$tblName = $row[array_keys($row)[0]];
		$deleteQuery = "DELETE FROM `$tblName` WHERE `starid` = '$starid';";
		// print("$deleteQuery<br>");
		
		$stmt = connect()->prepare($deleteQuery);
		$stmt->execute();
	}
}

// Also used to obtain name(s) for non-association tables
function forRefTbl($distinct) {
	$to_remove_for_ref_tbl_names = [
		'preferred ',
		' assoc tbl',
		' 1',
		' 2',
		' 3'
	];

	foreach ($to_remove_for_ref_tbl_names as $to_remove) {
		$distinct = str_replace($to_remove, '', $distinct);
	}

	// $distinct = str_replace(' ', '-', $distinct);

	return $distinct;
}

// returne an array that maps option id => option label
function readRefTbl($assocTblName){
	$tblNameNoSuffix = forRefTbl($assocTblName);
	$refTblName = "$tblNameNoSuffix ref tbl";

	$sqlQuery = "
		SELECT `$refTblName`.`$tblNameNoSuffix` as `option label`, `$refTblName`.`id` as `option id`
		FROM `$refTblName`;
	";

	try {
		$stmt = connect()->prepare($sqlQuery);

		if($stmt->execute()) {
			$stmt->execute();

			$options = [];
			foreach ($stmt->fetchAll() as $row) {
				$options[] = new Option($row[array_keys($row)[0]], $row[array_keys($row)[1]]);
				// $options[] = new Option(1, 'option');
			}
			
			return $options;
		}
		else {
			echo "Query Failed";
		}
	}
	catch(PDOException $e) {
		echo $e->getMessage();        
	}
}

function getGroupStr($isMentor) {
	$group = $isMentor == '1' ? 'mentor' : 'mentee';
	$groupOpposite = $isMentor == '1' ? 'mentee' : 'mentor';

	return [$group, $groupOpposite];
}

function echoMatchesRow($starid) {
	$isMentor = getParticipantInfo($starid, ['is mentor'])['is mentor'][0];
	$temp = getGroupStr($isMentor);
	$group = $temp[0];
	$groupOpposite = $temp[1];

	$temp = getParticipantInfo($starid, ['is active', 'max matches']);
	
	$maxMatches = $temp['max matches'][0];
	$isActive = $temp['is active'][0];

	echo "
		<span 
			id=\"participant-row-$starid\" 
			class=\"participant-row $group-row\" 
			onclick=\"\">";

	// delete icon
	echo "<i class=\"delete-icon fa-solid fa-xmark\" onclick=\"setSelectedStarid('$starid');showDeleteMatchesConfirmation();\"></i>";

	// is active
	echo $isActive == '1' ? '<i class="is-active-icon fa-solid fa-circle-check"></i>' : '<i class="is-inactive-icon fa-solid fa-circle-xmark"></i>';

	// max matches
	echo "<span class=\"max-matches\">$maxMatches</span>";

	// participant starid
	echo "
		<span 
			class=\"starid participant-starid $group-container\" 
			id=\"participant-starid-$starid\" onclick=\"setSelectedStarid('$starid');hideAllParticipantInfoExceptSelected();\">
				<span class=\"participant-starid-starid\">
					<span>$starid</span>
				</span>
		</span>";

	$matches = getStaridsOfMatches($starid, $isMentor);

	// match starids
	echo '<span class="match-starids">';
	if (count($matches) > 0) {
		foreach ($matches as $matchStarid) {
			$mentorStarid = $isMentor == '1' ? $starid : $matchStarid;
			$menteeStarid = $isMentor == '0' ? $starid : $matchStarid;
			
			$temp = getMatchInfo($mentorStarid, $menteeStarid, ['end date', 'extendable to date']);
			$endDate = date_create($temp['end date']);
			$endDate = date_format($endDate, 'm-d-Y');
			$extendableToDate = $temp['extendable to date'];

			echo "
				<span 
					style=\"display: inline-grid; grid-template-columns: 20% 80%;\"
					class=\"starid match-starid match-starid-$matchStarid $groupOpposite-container\" 
					onclick=\"setSelectedStarid('$matchStarid');hideAllParticipantInfoExceptSelected();\">

					<span 
						style=\"\"
						class=\"match-starid-starid\">
						<span>$matchStarid</span>
					</span>

					<span 
						style=\"display: grid; grid-template-rows: 50% 50%\"
						class=\"match-starid-dates\">

						<span style=\"display: grid; grid-template-columns: 50% 50%\">
							<span class=\"end\">End: 
							</span><span class=\"end-date\">$endDate</span>
						</span>

						<span style=\"display: grid; grid-template-columns: 50% 50%\">
							<span class=\"extendable-to\">Extendable to: </span>
							<span class=\"extendable-to-date\">$extendableToDate</span>
						</span>
					</span>

			</span>";
		}
	}
	echo '</span></span>';
}

function getAllParticipantStarids() {
	$participants = [];

	$sqlQuery = "SELECT `starid` FROM `participant`";

	$stmt = connect()->prepare($sqlQuery);
	$stmt->execute();

	foreach ($stmt->fetchAll() as $row) {
		$participants[] = $row[array_keys($row)[0]];
	}

	return $participants;
}

function getStaridsOfMatches($starid, $isMentor) {
	$matches = [];

	$temp = getGroupStr($isMentor);
	$group = $temp[0];
	$groupOpposite = $temp[1];

	$sqlQuery = "SELECT `$groupOpposite starid` FROM `mentorship` WHERE `$group starid` = '$starid'";

	$stmt = connect()->prepare($sqlQuery);
	$stmt->execute();

	foreach ($stmt->fetchAll() as $row) {
		$matches[] = $row[array_keys($row)[0]];
	}

	return $matches;
}

function getParticipantInfo($starid, $columns) {
	$participantTblCols = getParticipantFields();
	$results = [];
	
	foreach ($columns as $colName) {
		if (in_array($colName, $participantTblCols)) {
			$sqlQuery = "SELECT `$colName` FROM `participant` WHERE `starid` = '$starid'";

			$stmt = connect()->prepare($sqlQuery);
			$stmt->execute();

			// value is enclosed in array to be consistent with columns where there are multiple values, although we currently don't use this function for any of those columns
			$results[$colName] = [$stmt->fetchAll()[0][$colName]];
		}
		else {
			$colName = forRefTbl($colName);
			$sqlQuery = "
				select `$colName`
				from `$colName assoc tbl` join `$colName ref tbl`
					WHERE
						`$colName assoc tbl`.`starid` = '$starid' and
						`$colName assoc tbl`.`$colName id` = `$colName ref tbl`.`id`
			";

			$stmt = connect()->prepare($sqlQuery);
			$stmt->execute();

			$vals = [];
			foreach ($stmt->fetchAll() as $row) {
				$vals[] = $row[$colName];
			}
			$results[$colName] = $vals;
		}
	}

	return $results;
}

function getMatchInfo($mentorStarid, $menteeStarid, $infos) {
	$mentorshipTblCols = getMentorshipFields();
	$results = [];
	
	foreach ($infos as $info) {
		if ($info == 'extendable to date') {
			$mentorIsActiveQuery = "SELECT `is active` FROM `participant` WHERE `starid` = '$mentorStarid'";
			$stmt = connect()->prepare($mentorIsActiveQuery);
			$stmt->execute();
			$mentorIsActive = (int) $stmt->fetchAll()[0]['is active'];

			$menteeIsActiveQuery = "SELECT `is active` FROM `participant` WHERE `starid` = '$menteeStarid'";
			$stmt = connect()->prepare($menteeIsActiveQuery);
			$stmt->execute();
			$menteeIsActive = (int) $stmt->fetchAll()[0]['is active'];

			if (!($mentorIsActive && $menteeIsActive)) {
				$results[$info] = 'N/A';
				return $results;
			}

			$mentorGradDateQuery = "SELECT `graduation date` FROM `participant` WHERE `starid` = '$mentorStarid'";
			$menteeGradDateQuery = "SELECT `graduation date` FROM `participant` WHERE `starid` = '$menteeStarid'";

			$stmt = connect()->prepare($mentorGradDateQuery);
			$stmt->execute();
			$mentorGradDate = date('Y-m-d', strtotime($stmt->fetchAll()[0]['graduation date']));

			$stmt = connect()->prepare($menteeGradDateQuery);
			$stmt->execute();

			$menteeGradDate = date('Y-m-d', strtotime($stmt->fetchAll()[0]['graduation date']));

			$earlierDate = min($mentorGradDate, $menteeGradDate);
			$endDateQuery = "SELECT `end date` FROM `mentorship` WHERE `mentor starid` = '$mentorStarid' and `mentee starid` = '$menteeStarid'";

			$stmt = connect()->prepare($endDateQuery);
			$stmt->execute();

			$endDate = date('Y-m-d', strtotime($stmt->fetchAll()[0]['end date']));

			$results[$info] = $earlierDate > $endDate ? date('m-d-Y', strtotime($earlierDate)) : 'N/A';
		}
		else {
			$colName = $info;
			$sqlQuery = "SELECT `$colName` FROM `mentorship` WHERE `mentor starid` = '$mentorStarid' and `mentee starid` = '$menteeStarid'";

			$stmt = connect()->prepare($sqlQuery);
			$stmt->execute();

			$results[$info] = $stmt->fetchAll()[0][$colName];
		}
	}

	return $results;
}

function echoMatchesAsJson($matches) {
	$toEcho = '';
	
	$toEcho .= '[';
	
	foreach ($matches as $match) {
		$toEcho .= '{';

		foreach ($match as $col => $val) {
			$toEcho .= "\"$col\" : \"$val\", ";
				
		}

		$toEcho = substr($toEcho, 0, strlen($toEcho) - 2);
		$toEcho .= '}, ';
	}

	$toEcho = substr($toEcho, 0, strlen($toEcho) - 2);

	$toEcho .= ']';

	echo $toEcho;
}

function getAllParticipantsAsObjs() {
	$starids = getAllParticipantStarids();
	$participantObjs = [];

	$n = 1;
	foreach ($starids as $starid) {
		$p = new Participant($starid);

		$participantObjs[] = $p;
		$n++;
	}

	return $participantObjs;
}

function extendMatch($mentorStarid, $menteeStarid, $endDate) {
	$sqlQuery = "UPDATE `mentorship` 
		SET `end date` = '$endDate', `is extendable` = CASE WHEN '$endDate' < DATE(`earlier grad date`) THEN TRUE ELSE FALSE END
		WHERE `mentor starid` = '$mentorStarid' and `mentee starid` = '$menteeStarid'";

	$stmt = connect()->prepare($sqlQuery);
	$stmt->execute();
}

function insertToSignIn($values) {
	$sql = "INSERT INTO `sign in`(`email`) values (?)";
	try
	{
		$stmt = connect()->prepare($sql);
		$stmt->execute(array($values));
		$result = "Data Addition successful";
    // echo $result;

	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
}

function getSignIn($values) {
	$sql = "SELECT * FROM `sign in` WHERE `email` = ?";

	try {
			// $sql = "SELECT * from members WHERE `email` = ? AND `password` = ?";
			$stmt = connect()->prepare($sql);
			$stmt->execute($values);
			$result = $stmt->fetchAll();
			return $result;
	}
	catch (PDOException $e) {
			return $e->getMessage();
	}
}

function getSignInCount($values) {
	$sql = "SELECT COUNT(*) FROM `sign in` WHERE `email` = ?";
	try {
		// $sql = "SELECT * from members WHERE `email` = ? AND `password` = ?";
		$stmt = connect()->prepare($sql);
		$stmt->execute([$values]);
		$result = $stmt->fetchColumn();
		return $result;
	}
	catch (PDOException $e) {
		return $e->getMessage();
	}
}

function updateSignIn($values) {
	$sql = "UPDATE `sign in` SET `verification code` = ? WHERE `email` = ? ";
	try {
		$stmt = connect()->prepare($sql);
		$stmt->execute($values);
	}
	catch(PDOException $e) {
		echo $e->getMessage();
	}
}

function createVerificationCode() {
	$verificationCode = rand(100000,999999);;
	return $verificationCode;
}