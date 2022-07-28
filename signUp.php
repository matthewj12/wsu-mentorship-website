<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles/signUp.css">
    <link rel="stylesheet" href="styles/generalStyle.css">
    <script src="signUp.js"></script>
</head>

<body>
    <div class="form-container">
        <div class="form-title">
            <h3>Please Sign Up here</h3>
        </div>
        <form class="form" id = "signUp">
            <div class="form-field">
                <label for="firstName">First Name:</label>
                <input type="text" name="firstName" required>
            </div>

            <div class="form-field">
                <label for="lastName">Last Name:</label>
                <input type="text" name="lastName">
            </div>

            <div class="form-field">
                <label for="starID" class="form-label">StarID:</label>
                <input type="text" name="starID" class="form-input" required></input>
            </div>

            <div class="form-field">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" class="form-input" required></input>
            </div>

            <div class="form-field">
                <input type="submit" value="Sign Up">
            </div>

            <div class="link">
					<span>Already have an account?</span>
					<a href="login.php" class="heading3">Sign in here</a>
				</div>
        </form>
    </div>
</body>
</html>