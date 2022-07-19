<?php
	//We are gonna use PDO 
	require_once('includes/autoloader.inc.php');
	require_once('includes/functions.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Survey</title>
	<link rel="stylesheet" href="styles/survey.css">
	<script src="headerTemplate.js"></script>
	<script>
		function checkboxDefaultValues() {
			const all = document.getElementsByClassName("post-val-when-unchecked");

			for (let i = 0; i < all.length; i++) {
				if (all[i].checked) {
					document.getElementById(all[i].id + "-hidden").disabled = true;
				}
			}
		}
	</script>
</head>
<body>

	<div class="welcome-container">
		<h1 class="welcome">
			Welcome to the survey
		</h1>
	</div>

	<?php $connection = connect() ?>

	<div class="form-container">
		<form class="survey-form" method="post" action="includes/insertParticipant.inc.php">

			<!-- is active -->
			<div class="survey-item">
				<label class="survey-item-label">
					Are you currently seeking a mentor/mentee? (If not, we will store your data until you are ready)
				</label>

				<input id="is-active-t" name="is active" type="radio" value="1" checked="checked">
				<label for="is-active-t">
					Yes
				</label>

				<input id="is-active-f" name="is active" type="radio" value="0">
				<label for="is-active-f">
					No
				</label>
			</div>

			<!-- is mentor -->
			<div class="survey-item">
				<label class="survey-item-label">
					Are you a mentor or a mentee?
				</label>

				<input id="is-mentor-t" name="is mentor" type="radio" value="1" checked="checked">
				<label for="is-mentor-t">
					Mentor
				</label>

				<input id="is-mentor-f" name="is mentor" type="radio" value="0">
				<label for="is-mentor-f">
					Mentee
				</label>
			</div>

			<!-- max matches -->
			<div class="survey-item">
				<label class="survey-item-label">
					What's the maximum number of mentees you want to be matched with? (ignore if you are a mentee)
				</label>

				<select name="max matches">
					<?php readEnumValues('option', 'mp', 'participant', 'max matches', 'max matches') ?>
				</select>
			</div>

			<!-- first name -->
			<div class="survey-item">
				<label class="survey-item-label">
					First Name
				</label>

				<input name="first name" type="text" value="John" required>
			</div>

			<!-- last name -->
			<div class="survey-item">
				<label class="survey-item-label">
					Last Name
				</label>

				<input name="last name" type="text" value="Doe" required>
			</div>

			<!-- star id -->
			<div class="survey-item">
				<label class="survey-item-label">
					StarID
				</label>

				<input name="starid" type="text" value="aaaaaaaa" required>
			</div>

			<!-- gender -->
			<div class="survey-item">
				<label class="survey-item-label">
					Gender
				</label>

				<select name="gender">
					<?php readEnumValues('option', 'mp', 'participant', 'gender', 'gender') ?>
				</select>
			</div>

			<!-- major -->
			<div class="survey-item">
				<label class="survey-item-label">What is your primary major?</label>

				<select name="major">
					<?php readEnumValues('option', 'mp', 'participant', 'major', 'major') ?>
				</select>

			</div>

			<!-- pre program -->
			<div class="survey-item">
				<label class="survey-item-label">
					What is your professional program in the College of Science and Enginnering?
				</label>

				<select name="pre program">
					<?php readEnumValues('option', 'mp', 'participant', 'pre program', 'pre program') ?>
				</select>
			</div>

			<!-- religion -->
			<div class="survey-item">
				<label class="survey-item-label">
					What is your religious affiliation?
				</label>

				<select name="religion">
					<?php readEnumValues('option', 'mp', 'participant', 'religious affiliation', 'religious affiliation') ?>
				</select>
			</div>

			<!-- race -->
			<div class="survey-item">
				<label class="survey-item-label">
					What race are you? (check all that apply)
				</label>

				<div class="checkbox-section-container">
					<?php readRefTable('checkbox', 'race', 'race', 'race') ?>
				</div>
			</div>

			<!-- misc. boolean data points -->
			<div class="survey-item">
				<label class="survey-item-label">
					Please check all that you are comfortable sharing.
				</label>

				<div class="checkbox-section-container">
					<?php
						$miscDataPoints = [
							"International student",
							"Student athlete",
							"Multilingual",
							"Not born in this country",
							"First in my family to attend college",
							"LGBTQ+",
							"Transferred from another college",
							"Unsure or undecided about my major"
						];

						foreach ($miscDataPoints as $dataPoint) {
							?>
							<div class="checkbox-section-container">
								<input id="<?php echo $dataPoint ?>" class="post-val-when-unchecked" type="checkbox" name="<?php echo $dataPoint ?>" value="1">

								<input id="<?php echo $dataPoint."-hidden" ?>" type="hidden" name="<?php echo $dataPoint ?>" value="0">

								<label for="<?php echo $dataPoint ?>">
									<?php echo $dataPoint ?>
								</label>
							</div>
						<?php
						}
					?>
				</div>
			</div>

			<!-- interested in diversity groups -->
			<div class="survey-item">
				<label class="survey-item-label">
					Are you associated or do you plan to associate with the International Office, Office of Equity and Inclusive Excellence, or TRIO?
				</label>

				<input id="interested-in-diversity-groups-t" name="interested in diversity groups" type="radio" value="1" checked>
				<label for="interested-in-diversity-groups-t">
					Yes
				</label>

				<input id="interested-in-diversity-groups-f" name="interested in diversity groups" type="radio" value="0">
				<label for="interested-in-diversity-groups-f">
					No
				</label>
			</div>

			<!-- hobbies -->
			<div class="survey-item">
				<label class="survey-item-label">
					What are your hobbies? (check all that apply)
				</label>

				<div class="checkbox-section-container">
					<?php readRefTable('checkbox', 'hobby', 'hobby', 'hobby') ?>
				</div>
			</div>

			<!-- second languages -->
			<div class="survey-item">
				<label class="survey-item-label">
					Which second languages, if any, do you know? (check all that apply)
				</label>

				<div class="checkbox-section-container">
					<?php readRefTable('checkbox', 'second language', 'second language', 'second language') ?>
				</div>
			</div>

			<!-- preferred gender -->
			<div class="survey-item">
				<label class="survey-item-label">
					Preferred gender
				</label>

				<select name="preferred gender">
					<?php readEnumValues('option', 'mp', 'participant', 'preferred gender', 'preferred gender') ?>
				</select>
			</div>

			<!-- preferred race -->
			<div class="survey-item">
				<label class="survey-item-label">
					Preferred race
				</label>

				<select name="preferred race">
					<?php readEnumValues('option', 'mp', 'participant', 'preferred race', 'preferred race') ?>
				</select>
			</div>

			<!-- preferred religious affiliation -->
			<div class="survey-item">
				<label class="survey-item-label">
					Preferred religious affiliation
				</label>

				<select name="preferred religious affiliation">
					<?php readEnumValues('option', 'mp', 'participant', 'preferred religious affiliation', 'preferred religious affiliation') ?>
				</select>
			</div>

			<!-- important qualities -->
			<div class="survey-item">
				<label class="survey-item-label">
					What are the most important qualities you want in a mentor/mentee?
				</label>
			

				<?php
				$descriptors = ["1st most important quality", "2nd most important quality", "3rd most important quality"];

				foreach ($descriptors as $descriptor) {
					?>
					<div class="dropdown-container">
						<select name="<?php echo $descriptor ?>">
							<?php readRefTable('option', 'important quality', 'important quality', 'important quality') ?>
						</select>
					</div>
					<?php
				}
				?>
			</div>

			<!-- misc. info -->
			<div class="survey-item">
				<label class="survey-item-label">
					Is there anything else you would like to share?
				</label>

				<textarea name="misc info" class="misc-info" type="text" size=80 required>I'm very special</textarea>
			</div>

			<input type="submit" name="submit_survey" value="Submit" onclick="checkboxDefaultValues()">

		</form>
	</div>



</body>
</html>
