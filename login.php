<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'composer\vendor\autoload.php';
require 'backend\static-files\php\functions.inc.php';
require 'backend\static-files\php\form-handlers.php';

$userInput = array('email' => null, 'password' => null, 'verification code' => null);
$signInErrors = array('email' => null, 'password' => null, 'signIn' => null, 'verification' => null);
$signInSuccess = array('email' => null, 'password' => null, 'signIn' => null, 'verification' => null);

//Use php mailer

if (isset($_POST['signUp'])) {
    $userInput['email'] = trim($_POST['email']);
    $userInput['password'] = trim($_POST['password']);

    //check empty input
    //if email is not empty
    if (checkEmpty($userInput['email']) == false) {
        //check email format
        //if email is valid
        if (checkEmail($userInput['email']) == true) {
            $signInSuccess['email'] = 'Valid Email';
            $signInSuccess['signIn'] = 'Signing you in...';
        } elseif (checkEmail($userInput['email']) == false) {
            $signInErrors['email'] = 'Invalid Email';
            $signInErrors['signIn'] = 'Sign In failed';
        }
    }

    //if email is empty
    elseif (checkEmpty($userInput['email']) == true) {
        $signInErrors['email'] = 'Email should not be blank.';
        $signInErrors['signIn'] = 'Sign In failed';
    }

    //if the email is valid and no errors
    if ($signInErrors['signIn'] == null) {
        //create activation code    
        
        //
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles/common.css">
    <script src="scripts/header-template.js"></script>
    <style>

    </style>

    <head>

    <body>
        <main>
            <div class="form-container" id="registration">
                <div class="form-title">
                    <h3>Please Sign in here</h3>
                </div>
                <form action="includes/login.inc.php" method="post">
                    <div class="form-field">
                        <label for="email" class="form-label">WSU email:</label>
                        <input type="text" name="email" class="form-input" required></input>
                        <p class="success email"><?php echo $signInSuccess['email'] ?></p>
                        <p class="error email"><?php echo $signInErrors['email'] ?></p>
                    </div>
                    <div class="form-field">
                        <input type="submit" value="Login" name="signUp">
                        <p class="success signIn"><?php $signInErrors['signIn'] ?></p>
                        <p class="error signIn"><?php $signInErrors['signIn'] ?></p>

                    </div>
                </form>
                <!-- <div class="link">
                        <span>Don't have an account yet?</span>
                        <a href="signup.php" class="heading3">Sign up here</a>
                    </div> -->
            </div>

            <div class="form-container" id="verification">
                <div class="form-title">
                    <h3>Email Verification</h3>
                </div>
                <form action="includes/login.inc.php" method="post">
                    <div class="form-field">
                        <p class="text">
                            Please enter the Verification code that has been sent to your WSU email.
                        </p>
                    </div>
                    <div class="form-field">
                        <label for="verificationCode" class="form-label">Enter Verification Code:</label>
                        <input type="text" name="verificationCode" class="form-input" required></input>
                        <p class="success verificationCode"><?php echo $signInSuccess['verification'] ?></p>
                        <p class="error verificationCode"><?php echo $signInErrors['verification'] ?></p>
                    </div>
                    <div class="form-field">
                        <input type="submit" value="Verify" name="signUp">
                        <p class="success verification"><?php $signInErrors['verification'] ?></p>
                        <p class="error verification"><?php $signInErrors['verification'] ?></p>

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