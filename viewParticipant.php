<?php


//view, delete, modify participants' information here
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link href="styles/viewParticipant.css" rel="stylesheet">
</head>

<body>

	<header>
		<div class="welcome-container">
			<div class="welcome">
				View Participant Information Here
			</div>
		</div>
	</header>
	<main>
		<div class="main-container">
			<div>
				<form method="post" action="">
					<label for="starID">Search StarID:</label>
					<input name="starID" type="text"></input>
					<input type="submit" value="View Participant" name="viewParticipant">
				</form>
			</div>
			<span class = "divider">OR</span>
			<div>
				<form method="post" action="">
					<input type="submit" value="Show All Participants" name="showAll">
				</form>
			</div>


		</div>
	</main>

</body>

</html>