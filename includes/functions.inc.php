<?php


$columns = [
	'is active',
	'is mentor',
	'max matches',
	'first name',
	'last name',
	'starid',
	'gender',
	'major',
	'pre program',
	'religious affiliation',
	'international student',
	'lgbtq+',
	'student athlete',
	'multilingual',
	'not born in this country',
	'transfer student',
	'first gen college student',
	'unsure or undecided about major',
	'interested in diversity groups',
	'preferred gender',
	'preferred race',
	'preferred religious affiliation',
	'1st most important quality',
	'2nd most important quality',
	'3rd most important quality',
	'misc info'
];

$mentorshipColumns = ['mentor starid', 'mentee starid', 'start date', 'end date'];


function connect()
{
	$serverName = "localhost";
	$dbUsername = "root";
	$dbPassword = "Sql783knui1-1l;/klaa-9";
	$dbName = "mp";

	try
	{
		$dsn = 'mysql:host='.$serverName.';dbname='.$dbName;
		$pdo = new PDO($dsn, $dbUsername, $dbPassword);
		//setting fetch mode
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		//setting error mode
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					
		return $pdo;
	}
	catch(PDOException $e)
	{
		echo "Connection Failed:". $e->getMessage();
	}
}

//function to get single choice input from html form
//return array
function assignSingle($inputField)
{
	if(isset($_POST[$inputField]))
	{
		echo "This participant is a ". $_POST[$inputField];
		$inputField = $_POST[$inputField];
	}
	echo $inputField;
}

//function to get multiple choice information from html form 
//and return array
function converArrtoStr($inputArr)
{
	$str = implode(', ', $inputArr);
	return $str;
}

//function to get boolean array from html form
//e.g. comfortable sharing
//maybe like use case switchas
function assignBooleanArray($inputField)
{
	if(isset($_POST['$inputField']))
	{
	}
	return $inputField;
}


function insertIntoParticipantTable($dataPoints) {
	GLOBAL $columns;

	$sqlQuery = "INSERT INTO participant (`";

	foreach ($columns as $column) {
		$sqlQuery .= $column . "`, `";
	}

	$sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 4);

	$sqlQuery .= "`) values (";

	foreach ($dataPoints as $dataPoint) {
		$sqlQuery .= '"' . $dataPoint . '", ';
	}

	$sqlQuery = substr($sqlQuery, 0, strlen($sqlQuery) - 2);

	$sqlQuery .= ");";

	echo "<br>" . $sqlQuery . "<br>";

	try
	{
		$stmt = connect()->prepare($sqlQuery);
		$stmt->execute();
	}
	catch(PDOException $e)
	{
		echo "New record addition to <h3>Participant</h3> FAILED";
		echo $sqlQuery. $e->getMessage();
	}
}


function generateInputs($sqlQuery, $fieldType, $tableName, $columnName, $name, $options) {
	foreach ($options as $option)
	{
		switch ($fieldType) {
			case 'option':
				?>
				<option name="<?php echo $name ?>" value="<?php echo $option ?>">
					<?php echo $option ?>
				</option>
				<?php
				break;

			case 'checkbox':
				$nameArr = $name.'[]';
				?>
				<div class="checkbox-container">
					<input 
						id    = "<?php echo $columnName."-".$option ?>" 
						name  = "<?php echo $name ?>" 
						value = "1"
						type  = "checkbox">

					<label for="<?php echo $columnName."-".$option ?>">
						<?php echo $option ?>
					</label>
				</div>
				<?php
				break;

			default:
				echo "Invalid Input field";
		}
	}
}

function readEnumValues($fieldType, $dbName, $tableName, $columnName, $name)
{
	$sqlQuery = "
		SELECT
		SUBSTRING(COLUMN_TYPE, 6, LENGTH(COLUMN_TYPE) - 6) AS val
		FROM
			information_schema.COLUMNS      
		WHERE
			COLUMN_NAME = ?
		AND
			TABLE_NAME = ?
		AND
			TABLE_SCHEMA = ?
	";

	try {
		// ------------- This part should be able to be reused for readRefTable(), but $stmt->execute() only works when the arguments are passed into it via "?"s (even though we should just be able to put the three values into the $sqlQuery directly like we do in readRefTable() ).
		$stmt = connect()->prepare($sqlQuery);

		if ($stmt->execute([$columnName, $tableName, $dbName]))
		{
			// $stmt->execute([$columnName, $tableName, $dbName]);
			$values = $stmt->fetchAll();
			$options = str_getcsv($values[0]["val"], ',', "'");
		// -------------

			generateInputs($sqlQuery, $fieldType, $tableName, $columnName, $name, $options);
		}
		else
		{
			echo "Query Failed";
		}
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();        
	}

}

function readRefTable($fieldType, $tableName, $columnName, $name)
{
	// We should be able to construct $sqlQuery in readEnumValues() just like this
	$sqlQuery = "
		SELECT `{$columnName}`
		FROM `{$tableName}`
	";

	try {
		$stmt = connect()->prepare($sqlQuery);

		if($stmt->execute())
		{
			$stmt->execute();
			$result = $stmt->fetchAll();
			// $options = str_getcsv($values[0]["val"], ',', "'");

			$options = [];
			foreach ($result as $val) {
				$options[] = $val[array_keys($val)[0]];
			}
			generateInputs($sqlQuery, $fieldType, $tableName, $columnName, $name, $options);
		}
		else
		{
			echo "Query Failed";
		}
	}
	catch(PDOException $e)
	{
		echo $e->getMessage();        
	}


}

function selectMentorship()
{
	$sql = 'SELECT * from mentorship';
	try
	{
		$stmt = connect()->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll();
		if(!$rows)
		{
			echo "No matches so far";
		}
		else
		{
			return $rows;
			// var_export($rows);
			// echo "<br>";
			// foreach($rows as $match)
			// {
			// 	echo $match['mentor starid']. '<br>';
			// 	echo $match['mentee starid']. '<br>';
			// 	echo $match['start date']. '<br>';
			// 	echo $match['end date']. '<br>';
			// }
		}
	}
	catch(PDOException $e)
	{
		echo "New record addition to <h3>Participant</h3> FAILED";
		echo $sql. $e->getMessage();
	}
}
