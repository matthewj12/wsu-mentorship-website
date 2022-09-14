<?php

function connect() {
	$serverName = "localhost";
	$dbUsername = "root";
	$dbPassword = "";
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

function insertParticipant() {
	$starid = $_POST['participant_starid'];
	wipeParticipant($starid);
	// to be consistent with the association tables where there can be multiple entries, we want an array inside an array for a single participant
	$userData = ['participant' => [[]]];
	// special column only in `has important quality assoc tbl` needs special handling, -1 signifies that this is not the current table

	foreach ($_POST as $tblAndColName => $valToInsert) {
		if ($tblAndColName != "submit_survey") {
			$valToInsert = preg_replace('/[^a-z,0-9, ,\',.]/', '', $valToInsert);
			
			$split   = explode('_', $tblAndColName);
			// $tblName = str_replace('-', ' ', str_replace([' 1 ', ' 2 ', ' 3 '], '', $split[0]));
			$tblName = str_replace('-', ' ', $split[0]);
			$colName = str_replace('-', ' ', $split[1]);

			$rank = -1;
			if (str_contains($tblName, 'important quality')) {
				$rank = substr($tblName, 18, 1);
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

	// remove final backtick + comma + space
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

function insertToSignIn($values)
{
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