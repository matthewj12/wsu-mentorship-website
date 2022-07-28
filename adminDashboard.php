<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>
	<link rel="stylesheet" href="styles/adminDashboard.css">
	<script src="headerTemplate.js"></script>

	<head>

	<body>
		<header>
			<div class="welcome-container">
				<div class="welcome">
					Welcome to Admin Dashboard
				</div>
			</div>
		</header>

		<main>
			<div class="main-container">
				<form method = "post" action="includes/runMain.php">
					<label class = ""></label>
					<input type="submit"value = "Create Matches">
				</form>

				<form method ="post" action = "viewParticipant.php">
				<label class = ""></label>
					<input type="submit"value = "Check Participants">
				</form>

				<form method ="post" action = "checkMatches.php">
				<label class = ""></label>
					<input type="submit"value = "Check Matches">
				</form>
			</div>
		</main>

		<footer>

		</footer>
	</body>

</html>