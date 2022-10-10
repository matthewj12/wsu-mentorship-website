<?php
	require_once('backend/static-files/php/functions.inc.php');
	require_once('backend/static-files/php/classes.inc.php');

	session_start();

	// redirectIfNotLoggedIn('participant');

	if (!isset($_SESSION['participant-starid'])) {
		$_SESSION['participant-starid'] = 'aaa';
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Survey</title>
	<link rel="stylesheet" href="styles/survey.css">
	<script src="scripts/header-template.js"></script>
	<script src="scripts/survey-functions.js"></script>
</head>
<body>		
	<div class="navbar">
		<a href="index.php">Home</a>
	</div>
	
	<div class="welcome-container">
		<h1 class="welcome">
			Welcome to the survey
		</h1>
	</div>

	<div class="form-container">
		<form class="survey-form" method="post" action="backend/static-files/php/call-insert-participant.php">
			<?php

			$surveyItemGroups = [
				'textbox' => [
					// column name in database, text to display in survey webpage
					['first name', 'First Name:'],
					['last name',  'Last Name:']
					// ['starid',     'StarID:']
				],

				'date' => [
					['graduation date', 'Graduation/leaving WSU date']
				],

				'radio' => [
					[
						'is active',
						'Should we consider you for matches as of now (active)? If not, we will save your information until you are ready (inactive).',
						[new Option('Active', 1), new Option('Inactive', 0)]
					],
					[
						'is mentor',
						'Are you a mentor or mentee?',
						[new Option('Mentor', 1), new Option('Mentee', 0)]
					]
				],

				'checkbox bool' => [
					['international student',            'International student'],
					['lgbtq+',                           'LGBTQ+'],
					['student athlete',                  'Student athlete'],
					['multilingual',                     'Multilingual'],
					['not born in this country',         'Not born in this country'],
					['transfer student',                 'Transfer student'],
					['first generation college student', 'First generation college student'],
					['unsure or undecided about major',  'Unsure or undecided about major'],
					['interested in diversity groups',   'Interested in diversity groups']
				],

				'checkbox assoc tbl' => [
					['hobby',           'What are your hobbies?'],
					['major',           'What are your major(s)?'],
					['pre program',     'What are your pre program(s)?'],
					['race',            'What race(s) are you?'],
					['second language', 'Select any second language(s) that you speak.']
				],

				'dropdown' => [
					['max matches',           'What is the maximum number of mentors/mentees you want to be matched with?'],
					['gender',                'Select your gender.'],
					['religious affiliation', 'Select your religious affiliation.'],
					['important quality 1',   'What is the first most important quality you value in a mentor/mentee?'],
					['important quality 2',   'What is the second most important quality you value in a mentor/mentee?'],
					['important quality 3',   'What is the third most important quality you value in a mentor/mentee?']
				],

				'textarea' => [
					['misc info', 'Is there anything else we should know when finding a mentor/mentee for you?']
				]
			];

			foreach ($surveyItemGroups as $type => $argsLists) {
				foreach ($argsLists as $args) {
					$colName = $args[0];
					// if ($type == 'dropdown' || $type == 'checkbox assoc tbl') {
					// 	$colName .= ' assoc tbl';
					// }

					$desc = $args[1];

					$options = null;
					if (count($args) == 3) {
						$options = $args[2];
					}
					
					$surveyItem = new SurveyItem($type, $colName, $desc, $options);
					$surveyItem->echoHtml();
				}
			}

			?>

			<input type="submit" value="Submit Survey">
		</form>
	</div>

	<script>
		formatMiscDataPoints();
		addIsMentorListeners();
		addNoDuplicateIqListeners();
		changeIqOptionsUntilNoDuplicates();
	</script>
</body>
</html>
