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
	$userData = [];

	foreach ($_POST as $k => $v) {
		print($k."<br>");
		if ($k != "submit_survey") {
			$k = strtolower($k);
			// participant
			if (!is_array($v)) {
				$v = strtolower($v);

				$userData[$k] = $v;
			}
			// hobbies, second languages, races
			else {
				// print($k." is arr");
				// print_r($v);
				// print("<br>");

				// $userData[$k] = $v;
			}
		}
	}
	
	$columns = getParticipantFields();
	$sqlQuery = "INSERT INTO participant (`";

	foreach ($columns as $column) {
		$sqlQuery .= $column . "`, `";
	}

	$sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 4);
	$sqlQuery .= "`) values (";

	foreach ($userData as $dataPoint) {
		$sqlQuery .= '"' . $dataPoint . '", ';
	}

	$sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 2);
	$sqlQuery .= ");";
	echo "<br>" . $sqlQuery . "<br>";

	try {
		$stmt = connect()->prepare($sqlQuery);
		$stmt->execute();
	}
	catch(PDOException $e) {
		echo "New record addition to <h3>Participant</h3> FAILED";
		echo $sqlQuery. $e->getMessage();
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
		}
		$this->colName = str_replace(' ', '-', $colName);
		$this->desc    = $desc;

		if ($type == 'radio') {
			$this->options = $options;
		}
		else {
			// ref table
		}

		
	}

	public function echoHtml() {
		$html = '<div class="survey-item">';

		// Group the checkbox bool data points together.
		if ($this->type != "checkbox bool") {
			$html .= "<label class=\"survey-item-label\">\n";
			$html .= "\t$this->desc\n";
			$html .= "</label>\n";
		}


		$tblNameHyph = str_replace(' ', '-', $this->tblName);
		$name = 'name="' . $tblNameHyph . '"';

		switch ($this->type) {
			case "radio":
				foreach ($this->options as $option) {
					$value  = "value=\"$option->value\"";
					$id_val = "$this->colName.$option->value";
					$id     = "id=\"$id_val\"";
					$type   = 'type="radio"';
					
					$html .= "<input $name $value $id $type>\n";
					$html .= "\n";
					
					// ------------------------------------------

					$for   = "for=\"$id_val\"";

					$html .= "<label $for>\n";
					$html .= "\t$option->label";
					$html .= "</label>\n";
				}
				break;

			case "textbox":
				$id = "$this->colName";
				
				$html .= "<input $name $id type=\"text\">";

				break;

			case "dropdown":
				$this->options = readRefTbl($this->tblName);
				
				$html .= "<select $name>";

				foreach ($this->options as $option) {
					$value  = "value=\"$option->value\"";
					$id_val = "$this->colName.$option->value";
					$id     = "id=\"$id_val\"";
					$for    = "for=\"$id_val\"";
					
					$html .= " $<option $value $id>$option->label</option>";
					$html .= "\n";
					
				}

				$html .= "</select>";

				break;

			case "checkbox bool":
				$value  = "value=\"1\"";
				$id_val = "$this->colName.1";
				$id     = "id=\"$id_val\"";
				$for    = "for=\"$id_val\"";
				$type   = 'type="checkbox"';
				
				$html .= "<input $name $value $id $type>";
				$html .= "\n";

				// ------------------------------------------

				$for   = "for=\"$id_val\"";

				$html .= "<label $for>\n";
				$html .= "\t$this->desc";
				$html .= "</label>";
				$html .= "<br>";

				break;

			case "checkbox assoc tbl":
				// "options" in this context refers to checkboxes
				$this->options = readRefTbl($this->tblName);
				
				foreach ($this->options as $option) {
					$value  = "value=\"$option->value\"";
					$id_val = "$this->colName.$option->value";
					$id     = "id=\"$id_val\"";
					$for    = "for=\"$id_val\"";
					$type   = 'type="checkbox"';
					
					$html .= "<input $name $value $id $type>";
					$html .= "\n";

					// ------------------------------------------

					$for   = "for=\"$id_val\"";

					$html .= "<label $for>\n";
					$html .= "\t$option->label";
					$html .= "</label>";
					$html .= "<br>";
				}

				$html .= "</select>";

				break;

			case "textarea":
				$html .= "<textarea $name id=\"$this->colName\" rows=\"8\" cols=\"60\">\n";
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
			$result = $stmt->fetchAll();

			$options = [];
			foreach ($result as $row) {
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
