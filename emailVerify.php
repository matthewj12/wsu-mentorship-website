<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles/common.css">
    <script src="scripts/header-template.js"></script>
    <style>
    /*first set the email verification container as display none*/

    </style>

    <head>

    <body>
        
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
                        <input type="text" name="verificationCode" class="form-input" required><?php echo $userInput['email']?></input>
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