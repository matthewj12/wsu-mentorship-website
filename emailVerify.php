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
    } else if (checkEmpty($verificationCode) == false) {
        // if (checkCode($verificationCode) == false) {
        //     $verifyError['verificationCode'] = "Wrong Verification Code";
        // } else if (checkCode($verificationCode) == true) {
        //     $verifySuccess['verificationCode'] = "Valid Verification Code";
        //     $verifySuccess['verifyAttempt'] = "Forms are all validated...";
        // }
        $verifySuccess['verificationCode'] = "Valid Verification Code";
        $verifySuccess['verifyAttempt'] = "Forms are all validated...";
    }

    echo $verifyError['verifyAttempt'];
    echo $verifySuccess['verifyAttempt'];

    if (($verifySuccess['verifyAttempt'] == 'Forms are all validated...') && ($verifyError['verifyAttempt'] == null)) {
        //retrieve the login record
        echo $_SESSION['email'];
        $rowFound = getParticipant(array($_SESSION['email']));
        var_dump($rowFound);

        if (count($rowFound) == 1) {
            // $rowFound = getParticipant(array($_SESSION['email'], $verificationCode));
            // var_dump($rowFound);
            if ($rowFound[0]['verification code'] == $verificationCode) {
                $verifySuccess['verifyAttempt'] = "Verification Successful";
                $_SESSION['logged in'] = true;
                header("refresh:3;homepage.php");
            } else if ($rowFound[0]['verification code'] != $verificationCode) {
                header("location:emailVerify.php?error=wrongVerificationCode");
            }
        } else if (count($rowFound) == 0) {
            header("location:../login copy.php?error=notloggedIn");
        }
    } else {
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

    <script src="scripts/header-template.js"></script>
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
            <form action="" method="post">
                <div class="form-field">
                    <p class="text">
                        Please enter the Verification code that has been sent to your WSU email.
                    </p>
                </div>
                <div class="form-field">
                    <label for="verificationCode" class="form-label">Enter Verification Code:</label>
                    <input type="text" name="verificationCode" class="form-input" required></input>
                    <p class="success verificationCode-success"><?php echo $verifySuccess['verificationCode'] ?></p>
                    <p class="error verificationCode-error"><?php echo $verifyError['verificationCode'] ?></p>
                </div>
                <div class="form-field">
                    <input type="submit" value="Verify" name="verify">
                    <p class="success verify-success"><?php $verifySuccess['verifyAttempt'] ?></p>
                    <p class="error verify-error"><?php $verifyError['verifyAttempt'] ?></p>

                </div>
            </form>
            <div class="link">
                <span>Resend Code?</span>
                <a href="signup.php" class="heading3">Sign up here</a>
            </div>
        </div>
        </main>
    </body>

</html>