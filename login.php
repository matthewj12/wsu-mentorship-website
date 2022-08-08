<?php

$signUpErrors = array('fname' => null, 'lname' => null, 'email' => null, 'password' => null, 'birthday' => null, 'city' => null, 'state' => null, 'ph number' => null, 'signUpAttempt' => null);
$signUpSuccess = array('fname' => null, 'lname' => null, 'email' => null, 'password' => null, 'birthday' => null, 'city' => null, 'state' => null, 'ph number' => null, 'signUpAttempt' => null);
$firstName = $lastName =  $email = $password =  $birthday = $city = $state = $phNumber = "";
$success = '';

if (isset($_POST['signup'])) {
    session_start();
    $email = $_POST['useremail'];
    $password = $_POST["userpassword"];
    // $password = password_hash($_POST['userpassword'], PASSWORD_DEFAULT);
    echo "Email:". $email;
    echo "<br>";
    echo "Password:". $password;
    echo "<br>";
}
	
	//Check Empty and valid email
    if ($signUpContr->checkEmptyEmail() == false) {
        $signUpErrors['email'] = 'Email should not be blank';
        echo $signUpErrors['email'];
    } else {
        if ($signUpContr->checkValidEmail() == false) {
            $signUpErrors['email'] = 'Invalid Email';
            echo $signUpErrors['email'];
        }
        else if ($signUpContr->checkValidEmail() == true) {
            $signUpSuccess['email'] = 'Valid Email';
            echo $signUpSuccess['email'];
        }

    }

    //Check Empty and valid Password
    echo $signUpContr->checkEmptyPwd();
    echo "password strength" . $signUpContr->checkPwdstrength();
    if ($signUpContr->checkEmptyPwd() == false) {
        $signUpErrors['password'] = 'Password should not be blank';
        echo $signUpErrors['password'];
    } else {
        if ($signUpContr->checkPwdstrength() == false) {
            $signUpErrors['password'] = 'Password should be at least 12 characters, contain at least 1 uppercase, 1 lowercase, 1 special character, and 1 number';
            echo $signUpErrors['password'];
        }

        else if ($signUpContr->checkPwdstrength() == true) {
            $signUpSuccess['password'] = 'Valid Password';
            echo $signUpSuccess['password'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<link rel="stylesheet" href="styles/login.css">
	<script src="scripts/header-template.js"></script>
<head>
<body>
	<div class="form-container">
		<form>
                    <input type="email" name="useremail" placeholder="Email" value=<?php echo $email ?>>
                    <p class="error useremail-error"><?php echo $signUpErrors['email'] ?></p>
                    <p class="success useremail-success"><?php echo $signUpSuccess['email'] ?></p>
                    <br>

                    <input type="password" name="userpassword" placeholder="Password">
                    <p class="error userpassword-error"><?php echo $signUpErrors['password'] ?></p>
                    <p class="success userpassword-success"><?php echo $signUpSuccess['password'] ?></p>
                    <br>

			<br>
                    <button type="submit" class="check-inbtn" name="signup"><a href="admin-dashboard.php">Login</a></button>
                    <p class="success memberSignUp-success"><?php echo $signUpSuccess['signUpAttempt'] ?></p>
                    <p class="error memberSignUp-error"><?php echo $signUpErrors['signUpAttempt'] ?></p>
		</form>
	</div>
</body>
</html>
