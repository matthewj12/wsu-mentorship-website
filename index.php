<?php

session_start();

echo 'Email: '. $_SESSION['email'];
echo 'logged in'. $_SESSION['logged in'];

// Check if the user is logged in, if not then redirect him to landing paget
if (!isset($_SESSION['logged in']) || $_SESSION['logged in'] != true) {
    header("location:../login.php?error=notloggedIn");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Home</title>
	<link rel="stylesheet" href="styles/index.css">
	<script src="scripts/header-template.js"></script>

	<head>

	<body>
		<button class="survey-btn" type="submit" name="survey_btn">
			<a href="survey.php">
				Start Survey
			</a>
		</button>

		<button class="login-btn" type="submit" name="login_btn">
			<a href="login.php">
				Login
			</a>
		</button>
	</body>

</html>