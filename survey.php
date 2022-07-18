<?php
	//We are gonna use PDO 
	require_once('includes/autoloader.inc.php');
	require_once('includes/functions.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Survey</title>
	<link rel="stylesheet" href="survey.css">
	<script src="headerTemplate.js"></script>
</head>
<body>

	<div class="welcome-container">
		<h1 class="welcome">
			Welcome to the survey
		</h1>
	</div>

	<?php $connection = connect(); ?>

	<div class="form-container">
		<form class="survey-form" method="post" action="includes/participant.inc.php">

			<!-- is active -->
			<div class="survey-item">
				<label class="survey-item-label">
					Are you currently seeking a mentor/mentee? (If not, we will store your data until you are ready)
				</label>

				<input id="is-active-t" name="is-active" type="radio" value="1">
				<label for="is-active-t">
					Yes
				</label>

				<input id="is-active-f" name="is-active" type="radio"  value="0">
				<label for="is-active-f">
					No
				</label>
			</div>

			<!-- is mentor -->
			<div class="survey-item">
				<label class="survey-item-label">
					Are you a mentor or a mentee?
				</label>

				<input id="is-mentor-t" name="is-mentor" type="radio" value="1">
				<label for="is-mentor-t">
					Mentor
				</label>

				<input id="is-mentor-f" name="is-mentor" type="radio" value="0">
				<label for="is-mentor-f">
					Mentee
				</label>
			</div>

			<!-- first name -->
			<div class="survey-item">
				<label class="survey-item-label">
					First Name
				</label>

				<input type="text" required>
			</div>

			<!-- last name -->
			<div class="survey-item">
				<label class="survey-item-label">
					Last Name
				</label>

				<input name="last name" type="text" required>
			</div>

			<!-- star id -->
			<div class="survey-item">
				<label class="survey-item-label">
					StarID
				</label>

				<input type="text" required>
			</div>

			<!-- gender -->
			<div class="survey-item">
				<label class="survey-item-label">
					Gender
				</label>

				<select class="form-field" default="0">
					<?php readEnumValues('option', 'mp', 'participant', 'gender', 'gender'); ?>
				</select>
			</div>

			<!-- major -->
			<div class="survey-item">
				<label class="survey-item-label">What is your primary major?</label>

				<select class="form-field" default="0">
					<?php readEnumValues('option', 'mp', 'participant', 'major', 'major'); ?>
				</select>
			</div>

			<!-- pre program -->
			<div class="survey-item">
				<label class="survey-item-label">
					What is your professional program in the College of Science and Enginnering?
				</label>

				<select class="form-field">
					<?php readEnumValues('option', 'mp', 'participant', 'pre program', 'pre program'); ?>
				</select>
			</div>

			<!-- religion -->
			<div class="survey-item">
				<label class="survey-item-label">
					What is your religious affiliation?
				</label>

				<select class="form-field">
					<?php readEnumValues('option', 'mp', 'participant', 'religious affiliation', 'religious affiliation'); ?>
				</select>
			</div>

			<!-- misc. boolean data points -->
			<div class="survey-item">
				<label class="survey-item-label">
					Please check all that you are comfortable sharing.
				</label>

				<div class="checkbox">
					<input type="checkbox" name="international student" value="1">
					<label for="international student">International student</label>
				</div>
				<div class="checkbox">
					<input type="checkbox" name="student athlete" value="1">
					<label for="student athlete">Student athlete</label>
				</div>
				<div class="checkbox">
					<input type="checkbox" name="multilingual" value="1">
					<label for="multilingual">Speak two or more languages</label>
				</div>
				<div class="checkbox">
					<input type="checkbox" name="not born in this country" value="1">
					<label for="not born in this country">Not born in this country</label>
				</div>
				<div class="checkbox">
					<input type="checkbox" name="first gen college student" value="1">
					<label for="first gen college student">First in my family to attend college</label>
				</div>
				<div class="checkbox">
					<input type="checkbox" name="lgbtq+" value="1">
					<label for="lgbtq+">LGBTQ+</label>
				</div>
				<div class="checkbox">
					<input type="checkbox" name="transfer student" value="1">
					<label for="transfer student">Transferred from another college</label>
				</div>
				<div class="checkbox">
					<input type="checkbox" name="unsure or undecided about major" value="1">
					<label for="unsure or undecided about major">Unsure or undecided about my major</label>
				</div>
			</div>

			<!-- interested in diversity groups -->
			<div class="survey-item">
				<label class="survey-item-label">
					Are you associated or do you plan to associate with the International Office, Office of Equity and Inclusive Excellence, or TRIO?
				</label>

				<input id="interested-in-diversity-groups-t" name="interested-in-diversity-groups" type="radio" value="1">
				<label for="interested-in-diversity-groups-t">
					Yes
				</label>

				<input id="interested-in-diversity-groups-f" name="interested-in-diversity-groups" type="radio" value="0">
				<label for="interested-in-diversity-groups-f">
					No
				</label>
			</div>

			<!-- preferred gender -->
			<div class="survey-item">
				<label class="survey-item-label">
					Preferred gender
				</label>

				<select class="form-field" default="0">
					<?php readEnumValues('option', 'mp', 'participant', 'preferred gender', 'preferred gender'); ?>
				</select>
			</div>

			<!-- preferred race -->
			<div class="survey-item">
				<label class="survey-item-label">
					Preferred race
				</label>

				<select class="form-field" default="0">
					<?php readEnumValues('option', 'mp', 'participant', 'preferred race', 'preferred race'); ?>
				</select>
			</div>

			<!-- preferred religious affiliation -->
			<div class="survey-item">
				<label class="survey-item-label">
					Preferred religious affiliation
				</label>

				<select class="form-field" default="0">
					<?php readEnumValues('option', 'mp', 'participant', 'preferred religious affiliation', 'preferred religious affiliation'); ?>
				</select>
			</div>
			
			<!-- important qualities -->
			<div class="survey-item">
				<label class="survey-item-label">
					What are the most important qualities you want in a mentor/mentee?
				</label>

				<div class="important-quality-container">
					<label class="important-quality-label">
						1st most important quality:
					</label>
					<select class="form-field" default="0">
						<?php readRefTable('option', 'important quality', 'important quality', 'important quality'); ?>
					</select>
				</div>

				<div class="important-quality-container">
					<label class="important-quality-label">
						2nd most important quality:
					</label>
					<select class="form-field" default="0">
						<?php readRefTable('option', 'important quality', 'important quality', 'important quality'); ?>
					</select>
				</div>

				<div class="important-quality-container">
					<label class="important-quality-label">
						3rd most important quality:
					</label>
					<select class="form-field" default="0">
						<?php readRefTable('option', 'important quality', 'important quality', 'important quality'); ?>
					</select>
				</div>
			</div>

			<!-- misc. info -->
			<div class="survey-item">
				<label class="survey-item-label">
					Is there anything else you would like to share?
				</label>

				<textarea class="misc-info" type="text" size=80 required></textarea>
			</div>

		</form>
	</div>



</body>
</html>
