<style>
	* {
		font-family: monospace;
	}
</style>

<?php

require_once("functions.inc.php");

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

insertIntoParticipantTable($userData);