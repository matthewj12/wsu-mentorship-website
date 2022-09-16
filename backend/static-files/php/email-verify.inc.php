<?php

require 'functions.inc.php';

session_start();

$email = $_SESSION['email'];
$verificationCode = $rowFound = $otpDetails = null;
$verificationCode = htmlspecialchars($_GET["verification"]);
// $email = htmlspecialchars($_GET["email"]);


//retrieve the login record
echo 'Email:'. $_SESSION['email'];
$rowFound = getSignIn(array($_SESSION['email']));
var_dump($rowFound);
if (count($rowFound) == 1) {
    echo "User already found";
    if ($rowFound[0]['verification code'] == $verificationCode) {
        $_SESSION["verification code"] = $verificationCode;
        $_SESSION['logged in'] = true;
        $otpDetails = array("email" => $_SESSION["email"], "verification code" => $verificationCode);
        echo json_encode($otpDetails);
        $_SESSION['logged in'] = true;
        echo "verfication code matches";
        header("location:C:WSU-CoSE-Mentorship-Project/index.php?email=$email&loggedIn=true");
    } else if ($rowFound[0]['verification code'] != $verificationCode) {
        echo "verification code does not match";
        header("email-Verify.php?error=wrongVerificationCode");
    }

} else if (count($rowFound) == 0) {
    echo "User not found. Log in a";
    header("location:login.php?error=notloggedIn");
}

?>