<?php

session_start();

// Check if the user is logged in, if not then redirect him to landing paget
if (!isset($_SESSION['logged in']) || $_SESSION['logged in'] != true) {
    header("location:../login.php?error=notloggedIn");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome to WSU MENTORSHIP PROGRAM</h1>
    <h2>User Credentials</h2>
    <?php
        echo "Email: ". $_SESSION['email'];
        echo '<br>';
        echo "Logged in?: ". $_SESSION['logged in'];
    ?>
</body>
</html>