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
    if ($signInSuccess['signIn'] != null && $signInErrors['signIn'] == null) {
        echo "Success";
        echo "Sign in success";
        //check if this email exists in participant
        $rowFound = getParticipantCount($userInput['email']) . 'rows found.';
        echo $rowFound;
        //create activation code
        $verificationCode = createVerificationCode();
        updateParticipant(array($verificationCode, $userInput['email']));
        echo $verificationCode;

        //if no
        if ($rowFound == 0) {
            //add this participant and activation codeto the db \
            insertToParticipant($userInput['email']);
            updateParticipant(array($verificationCode, $userInput['email']));
        }

        //if yes
        else if ($rowFound == 1) {
            //update the activation code of this participant in db
            updateParticipant(array($verificationCode, $userInput['email']));
        }

        //send activation code to the participant's email using php mailer
        $name = "Mentorship Program";  // Name of your website or yours
        $to = "eimyatnoeaung98@gmail.com";  // mail of reciever
        $subject = "Verification Code";
        $body = "Enter this verification codesent". $verificationCode;
        $from = "";  // you mail
        $password = '';  // your mail password

        $mail = new PHPMailer();

        // To Here

        //SMTP Settings
        $mail->isSMTP();
        // $mail->SMTPDebug = 3;  Keep It commented this is used for debugging                          
        $mail->Host = "smtp.gmail.com"; // smtp address of your email
        $mail->SMTPAuth = true;
        $mail->SMTPDebug = 2;
        $mail->Username = $from;
        $mail->Password = $password;
        $mail->Port = 587;  // port
        $mail->SMTPSecure = "ssl";  // tls or ssl
        $mail->smtpConnect([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        //Email Settings
        $mail->isHTML(true);
        $mail->setFrom($from, $name);
        $mail->addAddress($to); // enter email address whom you want to send
        $mail->Subject = ("$subject");
        $mail->Body = $body;
        if ($mail->send()) {
            echo "Email is sent!";
        } else {
            echo "Something is wrong: <br><br>" . $mail->ErrorInfo;
        }

        //NOT WORKING

        // $mail = new PHPMailer(true);
        // try {
        //     //Server settings
        //     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        //     $mail->isSMTP();                                            //Send using SMTP
        //     $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        //     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        //     $mail->Username   = 'jadeaung286@gmail.com';                     //SMTP username
        //     $mail->Password   = '2828iou$shwo!1-vchilata';                               //SMTP password
        //     // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        //     $mail->SMTPSecure = "tls";
        //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        //     $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //     //Recipients
        //     $mail->setFrom('jadeaung286@gmail.com');
        //     $mail->addAddress('eimyatnoeaung98@gmail.com');     //Add a recipient

        //     //Content
        //     $mail->isHTML(true);                                  //Set email format to HTML
        //     $mail->Subject = 'Here is the subject';
        //     $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        //     $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        //     $mail->send();
        //     echo 'Message has been sent';
        // } catch (Exception $e) {
        //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        // }



        //then header to email verify.php

    }

    //if there are sign in errors
    // else
    // {
    //     //direct back to login.php
    //     header( "refresh:1;url=login.php");

    // }
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
                <form action="" method="post">
                    <div class="form-field">
                        <label for="email" class="form-label">WSU email:</label>
                        <input type="text" name="email" class="form-input" value=<?php echo $userInput['email'] ?>>
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