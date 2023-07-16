<!DOCTYPE html>


<?php
	require_once('backend/static-files/php/functions.inc.php');
	session_start();
	$evCodeLs = 5 * 60; // email verify code lifespan = 5 minutes

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		if (isSetAndNotEmptyGET('login-step-1')) {
			if (isSetAndNotEmptyGET('participant-starid')) {
				$_SESSION['participant-starid'] = $_GET['participant-starid'];
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
	<script src="scripts/header-template.js"></script>

	<link type="text/css" rel="stylesheet" href="styles/common.css">
	<link type="text/css" rel="stylesheet" href="styles/login.css">
	<script src="scripts/login-functions.js"></script>
</head>
<body>
	<?php
		displayNavbar($_SESSION);
	?>

	<h1>Login</h1>
	<!-- Enter admin code if admin or starid if participant. -->
	<form class="login" id="lg-step-1-container" method="GET">
		<h2>I am a(n):</h2>

		<!-- "Login as" flipswitch -->
		<div id="fpp">
			<div id="lg-as-toggle">
				<span class="btn" id="i-am-part">Participant</span>
				<span class="btn" id="i-am-admin">Administrator</span>
			</div>
		</div>

		<div id="login-inputs">
			<div id="participant-login-inputs">
				<input maxlength="8" minlength="8" id="participant-starid-inp" type="text" name="participant-starid">
				<br>
				<label for="participant-starid-inp">Enter your starid</label>
			</div>

			<div id="admin-login-inputs">
				<input maxlength="8" minlength="8" id="admin-code-inp" type="text" name="admin-code">
				<br>
				<label for="admin-code-inp">Enter your 8-digit-long admin code</label>
				<p hidden id="invalid-admin-code">Invalid code.</p>
			</div>

		</div>

		<div class="btn-row">
			<input disabled class="btn" id="lg-step-1-submit-btn" type="submit" value="Submit" name="login-step-1">
		</div>
	</form>

	<!-- Email verification; only for participants; admins don't do this step. -->
	<form class="login" id="ev-container" method="GET">
		<div class="form-title">
			<h2>A verification code has been sent to <?php echo $_SESSION['participant-starid'] . '@go.minnstate.edu' ?>.</h2>
			<h3>This code will expire in 5 minutes.</h3>
		</div>
		<div class="form-field">
			<label for="email">Enter code:</label>
			<input type="text" name="ev-code" class="form-input" id="code">
			<p class="success"></p>
			<p class="error"></p>
		</div>
		<div class="btn-row">
			<div class="link">
				<a href="login.php?participant-starid=<?php echo $_SESSION['participant-starid'] ?>&admin-code=&login-step-1=Submit"><div class="btn" id="resend">Resend Code</div></a>
			</div>
			<div class="form-field">
				<input class="btn" type="submit" name="email-verify" value="Submit" id="ev-submit-btn">
				<p class="error"></p>
				<p class="success"></p>
			</div>
		</div>
	</form>

	<script>
		partBtn = document.getElementById("i-am-part");
		adminBtn = document.getElementById("i-am-admin");

		document.getElementById('admin-login-inputs').hidden = true;
		document.getElementById('participant-login-inputs').hidden = true;

		partBtn.addEventListener("click", showOnlyPartInputs);
		partBtn.addEventListener("click", updateSubmitBtn);

		adminBtn.addEventListener("click", showOnlyAdminInputs);
		adminBtn.addEventListener("click", updateSubmitBtn);
		
		partBtn.addEventListener("mouseout", mouseoutPart);
		partBtn.addEventListener("click", selectPart);
		partBtn.addEventListener("mouseover", mouseoverPart);

		adminBtn.addEventListener("mouseout", mouseoutAdmin);
		adminBtn.addEventListener("click", selectAdmin);
		adminBtn.addEventListener("mouseover", mouseoverAdmin);

		document.getElementById('participant-starid-inp').addEventListener("keydown", updateSubmitBtn);
		document.getElementById('admin-code-inp').addEventListener("keydown", updateSubmitBtn);

		// Without this line, the user has to type an extra character into the text box before the submit button is enabled since "onkeydown" means that the function looks at the length of the input BEFORE the current key's character is registered.
		setInterval(updateSubmitBtn, 50);

		// Go to login step 2 (the email verification part) if the participant user has entered their starid and clicked "submit"
		<?php
			echo isSetAndNotEmptyGET('participant-starid') ? "showEmailVerify();" : "showLoginStep1();";
		?>

	</script>

</body>
</html>