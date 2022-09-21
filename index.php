<?php
	session_start();
	print_r($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Home</title>
	<link rel="stylesheet" href="styles/index.css">
	<script src="scripts/header-template.js"></script>
<head>
<body>
	<button class="login-btn">
		<?php
			if (isset($_SESSION['participant-logged-in'])) {
				echo '
					<a href="participant-homepage.php">
						Participant Login
					</a>
				';
			}
			else {
				echo '
					<a href="login-participant.php">
						Participant Login
					</a>
				';
			}
		?>
	</button>

	<button class="login-btn">
		<?php
			if (isset($_SESSION['admin-logged-in'])) {
				echo '
					<a href="admin-dashboard.php">
						Admin Login
					</a>
				';
			}
			else {
				echo '
					<a href="login-admin.php">
						Admin Login
					</a>
				';
			}
		?>


	</button>
</body>
</html>
