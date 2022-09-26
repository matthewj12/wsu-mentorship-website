<?php
require_once('backend/static-files/php/functions.inc.php');

session_start();

$loginStep1Submitted = null;
$loginStep2Submitted = null;
$pd = $ps = $ac = $destAddr = $emailVerifyCode = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['post-data'] = $_POST;
    $pd = $_SESSION['post-data'];
    var_dump($_SESSION['post-data']);

    if (isset($pd['login-step-1-btn'])) {
        $ac = $pd['admin-code'];
        $ps = $pd['participant-starid'];
        if ($ac != '') {
            // echo ("Entered Admin Code: " . $ac . "<br>");
            $loginStep1Submitted = true;
            $loginStep2Submitted = false;
            if (isValidAdminCode($ac)) {
                unset($_SESSION['post-data']);
                header('Location: admin-dashboard.php');
                return;
            } else {
                // display error
                echo "admin code does not match";
                $loginStep1Submitted = false;
                $loginStep2Submitted = false;
            }
        }

        if ($ps != '') {
            // echo ("Entered star id " . $ps . "<br>");
            $loginStep1Submitted = true;
            $loginStep2Submitted = false;
            $smtpScriptPath = "backend/static-files/python/smtp.py";
            $destAddr = $ps . '@go.minnstate.edu';
            $emailVerifyCode = rand(100000, 999999);
            shell_exec("python $smtpScriptPath $destAddr $emailVerifyCode");
            updateSignIn($emailVerifyCode, $destAddr);
            $_SESSION['destAddr'] = $destAddr;
        }

    } else if (isset($pd['login-step-2-btn'])) {
        $code = $pd['code'];
        $loginStep1Submitted = true;
        $loginStep2Submitted = true;

        if (isCorrectVerificationCode($code, $_SESSION['destAddr'])) {
            unset($_SESSION['post-data']);
            $_SESSION['participant-logged-in'] = true;
            echo "verification code matches";
            header('Location: participant-dashboard.php');
            echo "this does not work";
        } else {
            echo "Verification code does not match. Sign in again.";
            $loginStep1Submitted = true;
            $loginStep2Submitted = false;

        }
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
    <link rel="stylesheet" href="styles/login.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
</style>

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

	<p id = "error-flag"></p>
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
        <input disabled id="submit-btn" type="submit" value="Submit" name="login-step-1-btn">
    </form>

    <!-- Enter the email verification code (only for participant's: admins don't need to verify their email) -->
    <form class="login" id="login-step-2" method="POST">
        <div class="form-title">
            <h3>Verification code is sent to your email.</h3>
            <h4>This code will expire in 1 minute.</h4>
        </div>
        <div class="form-field">
            <label for="email" class="form-label">Enter verification code here:</label>
            <input type="text" name="code" class="form-input" id="code" />
            <p class="success"></p>
            <p class="error"></p>
        </div>
        <div class="form-field">
            <input type="submit" name="login-step-2-btn" value="Submit" id="submitBtn" />
            <p class="error"></p>
            <p class="success"></p>
        </div>
        <div class="link">
            <button id="resend" onclick="directToLogin()">Login again to Resend Code?</button>
        </div>
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

        //to execute unsetOTP.php - change OTP to null in db
        function unsetOTP() {
            $.ajax({
                url: 'backend/static-files/php/unsetOTP.php',
                type: 'post',
                success: function(response) {
					$("#error-flag").html(
                        "Verification code expired."
                    )
                    $("#login-step-1").css("display", "block");
                    $("#login-step-2").css("display", "none")
                    console.log("OTP Unset");
                }
            });
        }

        //this works
        function directToLogin() {
            document.getElementById('login-step-1').style.display = "none";
            document.getElementById('login-step-2').style.display = "block";
        }

        $(document).ready(function() {
            setTimeout(unsetOTP, 30000);
        });
    </script>


    <!--show login-step-2 form and hide login-step-1 form-->
    <?php
    if ($loginStep1Submitted == 1 && $loginStep2Submitted == 0) {
        echo '<script>document.getElementById("login-step-2").style.display = "block";</script>';
        echo '<script>document.getElementById("login-step-1").style.display = "none";</script>';
    }

    if ($loginStep1Submitted == 1 && $loginStep2Submitted == 1) {
        echo '<script>document.getElementById("login-step-2").style.display = "none";</script>';
        echo '<script>document.getElementById("login-step-1").style.display = "none";</script>';
    }

    ?>

</body>

</html>