<?php

require 'composer\vendor\autoload.php';
require 'backend\static-files\php\functions.inc.php';
require 'backend\static-files\php\form-handlers.php';

session_start();

$email = null;
$signInErrors = array('email' => null, 'signIn' => null, 'verification' => null);
$signInSuccess = array('email' => null, 'signIn' => null, 'verification' => null);
$verificationCode = null;

if (isset($_POST['signUp'])) {
    $email = trim($_POST['email']);
    //check empty input
    //if email is not empty
    if (checkEmpty($email) == false) {
        //check email format
        //if email is valid - has to be @go.minnstate.edu
        if (checkEmail($email)) {
            $signInSuccess['email'] = 'Valid Email';
            $signInSuccess['signIn'] = 'Form fields are validated';
        }
        //if email is not valid
        elseif (checkEmail($email)) {
            $signInErrors['email'] = 'Invalid Email';
            $signInErrors['signIn'] = 'Sign In failed';
        }
    }

    //if email is empty
    elseif (checkEmpty($email)) {
        $signInErrors['email'] = 'Email should not be blank.';
        $signInErrors['signIn'] = 'Sign In failed';
    }

    //if there are no sign in errors
    if ($signInSuccess['signIn'] == 'Form fields are validated' && $signInErrors['signIn'] == null) {
        echo "Success";
        echo "Sign in success";
        //check if this email exists in participant
        $rowFound = getParticipantCount($email);
        $verificationCode = createVerificationCode();
        echo "Verification code: ".  $verificationCode;
        echo "<br>";

        //if no
        if ($rowFound == 0) {
            echo "new participant";
            //add this participant and activation codeto the db \
            echo "Insertion : " . insertToParticipant($email);
            echo "Update :" . updateParticipant(array($verificationCode, $email));
            echo "<br>";

        }

        //if yes
        else if ($rowFound == 1) {
            echo "signed up already";
            //update the activation code of this participant in db
            echo "Update: ". updateParticipant(array($verificationCode, $email));
            echo "<br>";
        }

        else
        {
            echo "Nothing works";
        }

        //send email
        try {
            $_SESSION['email'] = $email;
            $signInSuccess['signIn'] = "Signing you in...";
            echo shell_exec("python backend/static-files/python/smtp.py $email $verificationCode");
            header("refresh:3;emailVerify.php");
        } catch (Exception $e) {
            echo $e;
        }
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
    <script src="scripts/form-validation.js"></script>
    <style>
        
        <?php
        //Error Handlers
        //To show fname Error Message
        if ($signInErrors['email'] != null) {
        ?>.email-error {
            display: block;
        }

        <?php
        } else if ($signInSuccess['email'] != null) {
        ?>.email-success {
            display: block;
            /* border-color: green; */
        }

        <?php
        }

        if ($signInErrors['signIn'] != null) {
        ?>.signIn-error {
            display: block;
            text-align: center;
        }

        <?php
        } else if ($signInSuccess['signIn'] != null) {
        ?>.signIn-success {
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
                <form action="" method="post" id = "form">
                    <div class="form-field">
                        <label for="email" class="form-label" id = "email">WSU email:</label>
                        <input type="text" name="email" class="form-input" value=<?php echo $email?>>
                        <p class="success email-success"><?php echo $signInSuccess['email'] ?></p>
                        <p class="error email-error"><?php echo $signInErrors['email'] ?></p>
                    </div>
                    <div class="form-field">
                        <input type="submit" value="Login" name="signUp" id = "submit">
                        <p class="error signIn-error"><?php echo $signInErrors['signIn'] ?></p>
                        <p class="success signIn-success"><?php echo $signInSuccess['signIn'] ?></p>
                    </div>
                </form>

            </div>

        </main>
    </body>

</html>