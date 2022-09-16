<?php

require 'backend\static-files\php\functions.inc.php';
require 'backend\static-files\php\form-handlers.php';

session_start();

$verificationCode = null;
$verifySuccess = array('verificationCode' => null, 'verifyAttempt' => null);
$verifyError = array('verificationCode' => null, 'verifyAttempt' => null);

if (isset($_POST['verify'])) {
    $verificationCode = trim($_POST['verificationCode']);

    //form-handlers
    if (checkEmpty($verificationCode) == true) {
			$verifyError['verificationCode'] = "Verification Code Should not be blank";
			$verifyError['verifyAttempt'] = "Verification failed";
    }
		else if (checkEmpty($verificationCode) == false) {
			$verifySuccess['verificationCode'] = "Valid Verification Code";
			$verifySuccess['verifyAttempt'] = "Forms are all validated...";
    }

    // echo $verifyError['verifyAttempt'];
    // echo $verifySuccess['verifyAttempt'];

    if (($verifySuccess['verifyAttempt'] == 'Forms are all validated...') && ($verifyError['verifyAttempt'] == null)) {
			//retrieve the login record
			// echo $_SESSION['email'];
			$rowFound = getSignIn(array($_SESSION['email']));
			var_dump($rowFound);

			if (count($rowFound) == 1) {
				if ($rowFound[0]['verification code'] == $verificationCode) {
					$_SESSION["verification code"] = $verificationCode;
					$verifySuccess['verifyAttempt'] = "Verification Successful";
					$_SESSION['logged in'] = true;
					$otpDetails = array("email" => $_SESSION["email"], "verification code" => $verificationCode);
					// echo json_encode($otpDetails);
					header("refresh:3;home-page.php");
				}
				else if ($rowFound[0]['verification code'] != $verificationCode) {
					header("location:email-verify.php?error=wrongVerificationCode");
				}
			}
			else if (count($rowFound) == 0) {
			header("location:../login copy.php?error=notloggedIn");
			}
	}
	else {
		echo "Nothing works";
	}
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles/common.css">
    <link rel="stylesheet" href="styles/login.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="scripts/header-template.js"></script>
    <script>
        function unsetOTP() {
            $.ajax({
                url: 'unset-otp.php',
                type: 'post',
                success: function(response) {
                    // // Perform operation on the return value
                    $("#verifySignIn").html(
                        "Verification code expired."
                    )
                    // window.location.replace("Old link in the previous browser")
                    // $('#test').prop('disabled', true);
                    console.log(response);
                }
            });
        }

        $(document).ready(function() {
            setTimeout(unsetOTP, 300000);
        });

    </script>

    <style>
        <?php

        //To show fname Error Message
        if ($verifyError['verificationCode'] != null) {
        ?>.verificationCode-error {
            display: block;
        }

        <?php
        } else if ($verifySuccess['verificationCode'] != null) {
        ?>.verificationCode-success {
            display: block;
            /* border-color: green; */
        }

        <?php
        }

        if ($verifyError['verifyAttempt'] != null) {
        ?>.verify-error {
            display: block;
            text-align: center;
        }

        <?php
        } else if ($verifySuccess['verifyAttempt'] != null) {
        ?>.verify-success {
            display: block;
            text-align: center;
        }

        <?php
        }
        ?>
    </style>

    <head>
    <body>
        <div class="form-container" id="verification">
            <div class="form-title">
                <h3>Email Verification</h3>
            </div>
            <form action="" method="post" id="verifySignIn">
                <div class="form-field">
                    <p class="text">
                        Please enter the Verification code that has been sent to your WSU email. The code will expire in five minutes.
                    </p>
                </div>
                <div class="form-field">
                    <label for="verificationCode" class="form-label">Enter Verification Code:</label>
                    <input type="text" name="verificationCode" class="form-input" required></input>
                </div>
                <div class="form-field">
                    <input type="submit" value="Verify" name="verify">
                    <p class="success verify-success"><?php $verifySuccess['verifyAttempt'] ?></p>
                    <p class="error verify-error"><?php $verifyError['verifyAttempt'] ?></p>
                </div>
            </form>
            <div class="link">
              <span class="heading3"><a href = "login.php">Reenter Email</a></span>
              <!-- <span class="heading3">Resend Code</span> -->
            </div>
        </div>
        </main>


    </body>

</html>
