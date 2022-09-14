<?php
require_once('backend/static-files/php/functions.inc.php');
require_once('backend/static-files/php/classes.inc.php');

// Check if the user is logged in, if not then redirect him to landing paget
if (!isset($_SESSION['logged in']) || $_SESSION['logged in'] != true) {
	header("location:../login.php?error=notloggedIn");
	exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<title>Survey</title>
	<link rel="stylesheet" href="styles/survey.css">
	<script src="scripts/header-template.js"></script>
	<script src="scripts/functions.js"></script>
</head>

<body>
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
					['first name', 'First Name:'],
					['last name',  'Last Name:'],
					['starid',     'StarID:']
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
					],
					[
						'is residually matchable',
						'Should we consider you for residual matches? In the case that we can\'t find you a mentor/mentee when the primary round of matching occurs, this will allow you to still be matched with mentor/mentee who sign up after the deadline.',
						[new Option('Consider me for residual matches', 1), new Option('Don\'t consider me for residual matches', 0)]
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
					['major',           'What are your secondary major(s)?'],
					['pre program',     'What are your secondary pre program(s)?'],
					['race',            'What race(s) are you?'],
					['second language', 'Select any second language(s) that you speak.']
				],
				'dropdown' => [
					['max matches',           'What is the maximum number of mentor/mentee you want to be matched with?'],
					['gender',                'Select your gender.'],
					['religious affiliation', 'Select your religious affiliation.'],
					['important quality 1',   'What is the first most important quality you value in a mentor/mentee?'],
					['important quality 2',   'What is the second most important quality you value in a mentor/mentee?'],
					['important quality 3',   'What is the third most important quality you value in a mentor/mentee?']
				],
				'textarea' => [
					['misc info', 'Is there anything else we should know when finding a mentor/mentee for you uwu?']
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

			<input type="submit">
		</form>
	</div>

	<script>
		formatMiscDataPoints();
		addIsMentorListeners();
	</script>
</body>

</html>