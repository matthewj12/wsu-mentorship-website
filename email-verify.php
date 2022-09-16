<!DOCTYPE html>
<html>
<html lang="en">

<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles/common.css">
    <link rel="stylesheet" href="styles/login.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="scripts/header-template.js"></script>
    <script>
        //to execute unsetOTP.php - change OTP to null in db
        function unsetOTP() {
            $.ajax({
                url: 'backend/static-files/php/unsetOTP.php',
                type: 'post',
                success: function(response) {
                    // // Perform operation on the return value
                    $("#verifySignIn").html(
                        "Verification code expired."
                    )
                    console.log(response);
                }
            });
        }

        function directToLogin() {
            //get url variables
            const queryString = window.location.search;
            console.log(queryString);
            const urlParams = new URLSearchParams(queryString);
            const email = urlParams.get('email');
            console.log(email);
            //pass it to index
            console.log("Directed to login page");
            window.location.href = 'login.php?email=' + email;
            // window.location.href = 'login.php';
        }
        $(document).ready(function() {
            setTimeout(unsetOTP, 30000);
        });
    </script>
</head>

<body>
    <div class="form-container" id="verify">
        <div class="form-title">
            <h3>Verification code is sent to your email.</h3>
            <h4>This code will expire in 1 minute.</h4>
        </div>
        <form method="POST" action="" class="form-label" id="verifySignIn">
            <div class="form-field">
                <label for="email" class="form-label">Enter verification code here:</label>
                <input type="text" name="code" class="form-input" id="code" />
                <p class="success"></p>
                <p class="error"></p>
            </div>
            <div class="form-field">
                <input type="submit" name="send" value="Submit" id="submitBtn" />
                <p class="error"></p>
                <p class="success"></p>
            </div>
            <div class="link">
                <button id="resend" onclick="directToLogin()">Login again to Resend Code?</button>
            </div>
        </form>
        <script src="scripts/emailVerify.js"></script>
    </div>
</body>

</html>