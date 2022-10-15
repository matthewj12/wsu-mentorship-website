<?php
	require_once('backend/static-files/php/functions.inc.php');

	session_start();
	// echo '<h1 style="font-family: monospace">';
	// print_r($_GET);
	// echo '</h1>';
	// echo '<br>';
	// echo '<br>';

	$evCodeLs = 5 * 60; // email verify code lifespan = 5 minutes

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if (isSetAndNotEmptyGET('login-step-1')) {
			if (isSetAndNotEmptyGET('participant-starid')) {
				$smtpScriptPath = "backend/static-files/python/smtp.py";
				$destAddr = $_GET['participant-starid'] . '@go.minnstate.edu';
				$code = $_SESSION['ev-code'] = rand(100000, 999999); // email verify
				$_SESSION['ev-code-ts'] = time(); // timestamp

				shell_exec("python $smtpScriptPath $destAddr $code");
			}
			elseif (isSetAndNotEmptyGET('admin-code')) {
				if (isValidAdminCode($_GET['admin-code'])) {
					$_SESSION['admin-logged-in'] = true;

					sessionUnsetIfSet('participant-logged-in');

					header('Location: admin-dashboard.php');
				}
			}
		}
		// user entered code that was emailed to them and then clicked "submit"
		elseif (isset($_GET['email-verify'])) {
			if ($_GET['ev-code'] == $_SESSION['ev-code'] && time() - $_SESSION['ev-code-ts'] < $evCodeLs) {
				$_SESSION['participant-logged-in'] = true;
				$_SESSION['participant-starid'] = $_GET['participant-starid'];

				sessionUnsetIfSet('admin-logged-in');

				unset($_SESSION['ev-code']);
				unset($_SESSION['ev-code-ts']);

				header('Location: participant-dashboard.php');
			}
		}
	}
?>

<html>

<head>
	<meta charset="utf-8">
	<title>
		Login
	</title>

	<script src="scripts/header-template.js"></script>
	<link rel="stylesheet" href="styles/login.css">
	<script src="scripts/login-functions.js"></script>

</head>
</styl>

<body>
	<script>
	</script>

	<div class="navbar">
		<a href="index.php">Home</a>
	</div>

	<!-- Enter admin code if admin or starid if participant. -->
	<form class="login" id="lg-step-1-container" method="GET">
		I am a: <br>
		<input name="a" id="login-as-participant" value="participant" type="radio" checked>
		<label for="login-as-participant">Participant</label>
		<br>
		<input name="a" id="login-as-admin" value="administrator" type="radio">
		<label for="login-as-admin">Administrator</label>

		<div id="participant-login-inputs">
			<label for="participant-starid-inp">Enter your starid</label>
			<input id="participant-starid-inp" type="text" name="participant-starid">
		</div>

		<div id="admin-login-inputs">
			<label for="admin-code-inp">Enter the 8-digit-long admin code you've been given, consisting of numbers and lowercase letters.</label>
			<br>
			<input id="admin-code-inp" type="text" name="admin-code">
			<p hidden id="invalid-admin-code">Invalid code.</p>
		</div>

		<br>
		<br>
		<input disabled id="lg-step-1-submit-btn" type="submit" value="Submit" name="login-step-1">
	</form>

	<!-- Email verification; only for participants; admins don't do this step. -->
	<form class="login" id="ev-container" method="GET">
		<div class="form-title">
			<h3>A verification code has been sent to your email.</h3>
			<p>This code will expire in 5 minutes.</p>
			<p>Reload page to resend code.</p>
		</div>
		<div class="form-field">
			<label for="email" class="form-label">Enter code:</label>
			<input type="text" name="ev-code" class="form-input" id="code">
			<p class="success"></p>
			<p class="error"></p>
		</div>
		<div class="form-field">
			<input type="submit" name="email-verify" value="Submit" id="ev-submit-btn">
			<p class="error"></p>
			<p class="success"></p>
		</div>
		<!-- <div class="link">
			<button id="resend" onclick="toEmailVerify()">Login again to Resend Code?</button>
		</div> -->
	</form>

	<script>
		document.getElementById('admin-login-inputs').hidden = true;

		document.getElementById('login-as-admin').addEventListener("click", showOnlyAdminInputs);
		document.getElementById('login-as-participant').addEventListener("click", showOnlyParticipantInputs);

		document.getElementById('participant-starid-inp').addEventListener("input", disableOrEnableSubmit);
		document.getElementById('admin-code-inp').addEventListener("input", disableOrEnableSubmit);

		<?php
			echo isSetAndNotEmptyGET('participant-starid') != '' ? "showEmailVerify();" : "showLoginStep1();";
		?>

	</script>

</body>
</html>