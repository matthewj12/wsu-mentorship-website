<?php
	require_once('backend/static-files/php/functions.inc.php');

	session_start();

	// print('session: ');
	// print_r($_SESSION);
	// echo '<br><br>';

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$_SESSION['post-data'] = $_POST;
		unset($_POST);

		header("Location: login.php");
		return;
	}

	if (isset($_SESSION['post-data'])) {
		$pd = $_SESSION['post-data'];
		$ac = $pd['admin-code'];
		$ps = $pd['participant-starid'];

		if ($ac != '') {
			if (isValidAdminCode($ac)) {
				unset($_SESSION['post-data']);
				
				header('Location: admin-dashboard.php');
				return;
			}
			else {
				// display error
			}

		}

		if ($ps != '') {
			$smtpScriptPath = "backend/static-files/python/stmp.py";
			$destAddr = $ps . '@go.minnstate.edu';
			$emailVerifyCode = rand(100000, 999999);

			shell_exec("py $smtpScriptPath $destAddr $emailVerifyCode");

			echo "<script>document.getElementById('login-step-1').hidden = true;</script>";
			echo "<script>document.getElementById('login-step-2').hidden = false;</script>";
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>
		Login
	</title>
</head>
<body>
	<script>
		function hideAllStepsExcept(stepToShow) {
			let stepsToHide = document.getElementsByClassName('login');

			for (let i = 0; i < stepsToHide.length; i++) {
				stepsToHide[i].hidden = true;
			}

			document.getElementById('login-step-' + stepToShow).hidden = false;
		}
		
		function showOnlyAdminInputs() {
			document.getElementById('participant-login-inputs').hidden = true;
			document.getElementById('admin-login-inputs').hidden = false;

			document.getElementById('participant-starid-inp').value = '';
		}

		function showOnlyParticipantInputs() {
			document.getElementById('participant-login-inputs').hidden = false;
			document.getElementById('admin-login-inputs').hidden = true;

			document.getElementById('admin-code-inp').value = '';
		}

	</script>

		<!-- Enter admin code if admin or starid if participant -->
		<form class="login" id="login-step-1" method="POST">
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
				<!-- <label for="admin-email-addr-inp">Enter your full email address</label>
				<input type="text" name="admin-email-addr" id="admin-email-addr-inp"> -->
				<label for="admin-code-inp">Enter the 8-digit-long admin code you've been given, consisting of numbers and lowercase letters.</label>
				<br>
				<input id="admin-code-inp" type="text" name="admin-code">
				<p hidden id="invalid-admin-code">Invalid code.</p>
			</div>

			<br>
			<br>

			<input disabled id="submit-btn" type="submit" value="Submit">
		</form>

		<!-- Enter the email verification code (only for participant's: admins don't need to verify their email) -->
		<form class="login" id="login-step-2" method="POST">

		</form>

	<script>
		function disableOrEnableSubmit() {
			let idOfInputToCheck = document.getElementById('admin-login-inputs').hidden ? 'participant-starid-inp' : 'admin-code-inp';
			let inputElem = document.getElementById(idOfInputToCheck);
			let submitBtnElem = document.getElementById('submit-btn');

			submitBtnElem.disabled = inputElem.value == '';
		}


		document.getElementById('admin-login-inputs').hidden = true;

		document.getElementById('login-as-admin').addEventListener("click", showOnlyAdminInputs);
		document.getElementById('login-as-participant').addEventListener("click", showOnlyParticipantInputs);

		let inputIds = ['participant-starid-inp', 'admin-code-inp'];
		for (let i = 0; i < inputIds.length; i++) {
			document.getElementById(inputIds[i]).addEventListener("input", disableOrEnableSubmit);
		}

	</script>

</body>
</html>