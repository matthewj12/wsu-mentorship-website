<?php

require('functions.inc.php');

session_start();

$email = $rowFound = $verificationCode = null;
$email = htmlspecialchars($_GET["email"]);
echo 'Email: ' . $email;

//check if this email exists in participant
$rowFound = getSignInCount($email);
$verificationCode = createVerificationCode();
echo "Verification code: " .  $verificationCode;
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
    echo "Update: " . updateParticipant(array($verificationCode, $email));
    echo "<br>";
} else {
    echo "Nothing works";
}

//send email
try {
    $_SESSION['email'] = $email;
    // $signInSuccess['signIn'] = "Signing you in...";
    echo shell_exec("python ../python/smtp.py $email $verificationCode");
    header("location:/WSU-CoSE-Mentorship-Project/email-Verify.php?error=notloggedIn");
    // addtocart.php?ItemID='.$key.'"
} catch (Exception $e) {
    echo $e;
}


