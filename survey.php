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
	<script src="header-template.js"></script>
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
		<form class="survey-form" method="post" action="includes/insert-participant.inc.php">

	<p>Work in progress (will be updated in my next committ)</p>

</body>
</html>
