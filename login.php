<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>
	<link rel="stylesheet" href="styles/loginStyle.css">
	<link rel="stylesheet" href="styles/generalStyle
	.css">
	<script src="headerTemplate.js"></script>

	<head>

	<body>

		<header>

		</header>

		<main>

			<div class="form-container">
				<div class="form-title">
					<h3>Please Sign in here</h3>
				</div>
				<form action="includes/login.inc.php" method="post">
					<div class="form-field">
						<label for="starID" class="form-label">StarID:</label>
						<input type="text" name="starID" class="form-input" required></input>
					</div>
					<div class="form-field">
						<label for="password" class="form-label">Password:</label>
						<input type="password" name="password" class="form-input" required></input>
					</div>
					<div class="form-field">
						<input type = "submit" value = "Login">
						<!-- <button class="login">
							<a href="adminDashboard.php">Login</a>
						</button> -->
					</div>
				</form>
				<div class="link">
					<span>Don't have an account yet?</span>
					<a href="signup.php" class="heading3">Sign up here</a>
				</div>
			</div>
		</main>



	</body>

</html>