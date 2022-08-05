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
	catch(PDOException $e) {
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
	$userData = ['participant' => [[]]];

	foreach ($_POST as $tblAndColName => $valToInsert) {
		if ($tblAndColName != "submit_survey") {
			$split   = explode('_', $tblAndColName);
			$tblName = str_replace('-', ' ', $split[0]);
			$colName = str_replace('-', ' ', $split[1]);

			// create the array for the values of the table with name $name if it doesn't already exist
			if (array_search($tblName, array_keys($userData)) === false) {
				$userData[$tblName] = [];
			}

			$newEntry = [];

			if ($tblName == 'participant') {
				// We don't have all the values needed for the entire row
				$userData['participant'][0][$colName] = $valToInsert;
			}
			else {
				// We have all the values needed for the entire row
				$userData[$tblName][] = ['starid' => $starid, $colName => $valToInsert];

			}
		}
	}

	foreach ($userData as $tblName => $newEntries) {
		foreach ($newEntries as $newEntry) {
			doInsert($starid, $tblName, $newEntry);
		}
	}
}
	
function doInsert($starid, $tblName, $colsAndVals) {
	// $columns = getParticipantFields();

	$sqlQuery = "INSERT INTO `$tblName`(`";

	foreach (array_keys($colsAndVals) as $colName) {
		$sqlQuery .= $colName . "`, `";
	}

	$sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 4);
	$sqlQuery .= "`) VALUES (";

	foreach ($colsAndVals as $val) {
		$sqlQuery .= "'$val', ";
	}

	$sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 2) . ');';
	
	// echo "<br><p style=\"font-family: monospace; font-size: 22px\">$sqlQuery<br>";

	try {
		$stmt = connect()->prepare($sqlQuery);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "<p style=\"font-family: monospace; font-size: 22px\">";
		echo "New insertion to `$tblName` failed.<br><br> Query:<br><br>";
		echo "$sqlQuery<br><br>";
		echo $sqlQuery. $e->getMessage();
		echo "</p>";
	}
}

// Wipes participant's entries in `participant` and `[...] assoc tbl` tables
function wipeParticipant($starid) {
	$getTblsToWipeQuery = "SHOW TABLES WHERE Tables_in_mp != 'mentorship' AND INSTR(Tables_in_mp, 'ref tbl') = 0;";

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

function forRefTbl($distinct) {
	$to_remove_for_ref_tbl_names = [
		'primary ',
		'secondary ',
		'preferred ',
		' assoc tbl'
	];

	foreach ($to_remove_for_ref_tbl_names as $to_remove) {
		$distinct = str_replace($to_remove, '', $distinct);
	}

	// $distinct = str_replace(' ', '-', $distinct);

	return $distinct;
}

class Option {
	public string $label;
	public string $value;

	public function __construct($label, $value) {
		$this->label = $label;
		$this->value = $value;
	}
}

class SurveyItem {
	private string $type;
	private string $tblName;
	private string $colName;
	private string $desc;
	private $options;

	public function __construct($type, $colName, $desc, $options=null){
		$this->type    = $type;
		$this->tblName = 'participant';

		if ($type == 'dropdown' || $type == 'checkbox assoc tbl') {
			$this->tblName = "$colName assoc tbl";
			$colName .= ' id';
		}

		$this->colName = str_replace(' ', '-', $colName);

		$this->desc    = $desc;

		if ($type == 'radio') {
			$this->options = $options;
		}
	}

	public function echoHtml() {
		$extraClass = $this->type == 'checkbox bool' ? ' checkbox-bool' : '';
		$html = "<div class = \"survey-item$extraClass\">";

		$tblNameHyph = str_replace(' ', '-', $this->tblName);
		$nameVal = $tblNameHyph.'_'.$this->colName;

		// Group the checkbox bool data points together.
		if ($this->type != "checkbox bool") {
			$id = '';

			if ($tblNameHyph == 'max-matches-assoc-tbl') {
				$id = "id=\"max-matches-label\"";
			}
			$html .= "<label $id class = \"survey-item-label\">\n";
			$html .= "\t$this->desc\n";
			$html .= "</label>\n";
		}

		switch ($this->type) {
			case "radio":
				foreach ($this->options as $option) {
					$idVal  = $nameVal.'_'.$option->value;
					$id     = "id    = \"$idVal\"";
					$name   = "name  = \"$nameVal\"";
					$value  = "value = \"$option->value\"";
					$type   = 'type  = "radio"';
					
					$html .= "<input $name $value $id $type required>\n";
					$html .= "\n";
					
					// ------------------------------------------

					$for   = "for = \"$idVal\"";

					$html .= "<label $for>\n";
					$html .= "\t$option->label";
					$html .= "</label>\n";
				}
				break;

			case "textbox":
				$name   = "name = \"$nameVal\"";
				$id = $tblNameHyph.'_'.$this->colName;
				
				$html .= "<input $name $id type = \"text\" required>";

				break;

			case "dropdown":
				$this->options = readRefTbl($this->tblName);
				$name = "name = \"$nameVal\"";
				$html .= "<select $name id=\"$nameVal\">";

				foreach ($this->options as $option) {
					$idVal  = $nameVal.'_'.$option->value;
					$id     = "id = \"$idVal\"";
					$value  = "value = \"$option->value\"";
					$for    = "for = \"$idVal\"";
					
					$html .= " $<option $value $id required>$option->label</option>";
					$html .= "\n";
				}

				$html .= "</select>";

				break;

			case "checkbox bool":
				$idVal  = "$nameVal.1";
				$id     = "id = \"$idVal\"";
				$name   = "name = \"$idVal\"";
				$value  = "value = \"1\"";
				$for    = "for = \"$idVal\"";
				$type   = 'type = "checkbox"';
				
				$html .= "<input $name $value $id $type>";
				$html .= "\n";

				// ------------------------------------------

				$for   = "for = \"$idVal\"";

				$html .= "<label $for>\n";
				$html .= "\t$this->desc";
				$html .= "</label>";
				$html .= "<br>";

				break;

			case "checkbox assoc tbl":
				// "options" in this context refers to checkboxes
				$this->options = readRefTbl($this->tblName);
				
				foreach ($this->options as $option) {
					$idVal  = "$nameVal.$option->value";
					$id     =    "id = \"$idVal\"";
					$name   =  "name = \"$idVal\"";
					$value  = "value = \"$option->value\"";
					$for    =   "for = \"$idVal\"";
					$type   =  'type = "checkbox"';
					
					$html .= "<input $name $value $id $type>";
					$html .= "\n";

					// ------------------------------------------

					$for   = "for = \"$idVal\"";

					$html .= "<label $for>\n";
					$html .= "\t$option->label";
					$html .= "</label>";
					$html .= "<br>";
				}

				$html .= "</select>";

				break;

			case "textarea":
				$name = "name = \"$nameVal\"";
				
				$html .= "<textarea $name id = \"$this->colName\" rows = \"8\" cols = \"60\">\n";
				$html .= "Enter optional response\n";
				$html .= "</textarea>\n";
				break;
		}

		$html .= "</div>\n";
		$html .= "\n";
		echo $html;
	}
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
