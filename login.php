<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'composer\vendor\autoload.php';
require 'backend\static-files\php\functions.inc.php';
require 'backend\static-files\php\form-handlers.php';

session_start();

$userInput = array('email' => null);
$signInErrors = array('email' => null, 'signIn' => null, 'verification' => null);
$signInSuccess = array('email' => null, 'signIn' => null, 'verification' => null);
$verificationCode = null;

if (isset($_POST['signUp'])) {
    $userInput['email'] = trim($_POST['email']);
    //check empty input
    //if email is not empty
    if (checkEmpty($userInput['email']) == false) {
        //check email format
        //if email is valid - has to be @go.minnstate.edu
        if (checkEmail($userInput['email']) == true) {
            $signInSuccess['email'] = 'Valid Email';
            $signInSuccess['signIn'] = 'Signing you in...';

        } 
        //if email is not valid
        elseif (checkEmail($userInput['email']) == false) {
            $signInErrors['email'] = 'Invalid Email';
            $signInErrors['signIn'] = 'Sign In failed';
        }
    }

    //if email is empty
    elseif (checkEmpty($userInput['email']) == true) {
        $signInErrors['email'] = 'Email should not be blank.';
        $signInErrors['signIn'] = 'Sign In failed';
    }

    //if there are no sign in errors
    if($signInSuccess['signIn'] != null && $signInErrors['signIn'] == null)
    {
        echo "Sign in success";
        //check if this email exists in participant
        $rowFound = getParticipantCount($userInput['email']) . 'rows found.';
        //create activation code
        $verificationCode = createVerificationCode();
        echo $verificationCode;
            //if no
            if($rowFound == 0)
            {
                //add this participant and activation codeto the db \
                insertToParticipant($userInput['email']);       
            }

            //if yes
            else if($rowFound == 1)
            {
                //update the activation code of this participant in db
                
            }

            //send activation code to the participant's email using php mailer

    }

    //if there are sign in errors
    else
    {
        //direct back to login.php
        // header( "refresh:1;url=login.php");

    }
}

echo $signInErrors['email'];
echo $signInSuccess['email'];
echo $signInErrors['signIn'];
echo $signInErrors['signIn'];
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
        if ($signInErrors['email'] != null) {
        ?>.email-error {
            display: block;
        }
        <?php
        }

        else if($signInSuccess['email'] != null)
        {
            ?>
            .email-success
            {
                display: block;
                /* border-color: green; */
            }

            <?php
        }

        if($signInErrors['signIn'] != null)
        {
            ?>
            .signIn-error
            {
                display: block;
                text-align: center;
            }

            <?php
        }

        else if($signInSuccess['signIn'] != null)
        {
            ?>
            .signIn-success
            {
                display: block;
                text-align: center;
            }

            <?php
        }
        ?>
    </style>

    <head>

    <body>
        <main>
            <div class="form-container" id="registration">
                <div class="form-title">
                    <h3>Log in with StarID@Winona.edu</h3>
                </div>
                <form action="" method="post">
                    <div class="form-field">
                        <label for="email" class="form-label">WSU email:</label>
                        <input type="text" name="email" class="form-input"></input>
                        <p class="success email-success"><?php echo $signInSuccess['email'] ?></p>
                        <p class="error email-error"><?php echo $signInErrors['email'] ?></p>
                    </div>
                    <div class="form-field">
                        <input type="submit" value="Login" name="signUp">
                        <p class="error signIn-error"><?php echo $signInErrors['signIn'] ?></p>
                        <p class="success signIn-success"><?php echo $signInSuccess['signIn'] ?></p>

                    </div>
                </form>

            </div>

        </main>
    </body>

</html>