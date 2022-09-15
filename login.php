
<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <link rel="stylesheet" href="styles/common.css">
  <link rel="stylesheet" href="styles/login.css">
  <!-- <link rel="stylesheet" href="style.css" /> -->
</head>

<body>
  <div class="form-container" id="registration">
    <div class="form-title">
      <h3>Log in with StarID@Winona.edu</h3>
    </div>
    <form method="POST" action="" id="form">
      <div class="form-field">
        <label for="email" class="form-label">WSU email:</label>
        <input type="text" name="email" class="form-input" id="email" />
        <p class="success"></p>
        <p class="error"></p>
      </div>

      <div class="form-field">
        <input type="submit" name="send" value="Submit" id="submitBtn" />
        <p class="error"></p>
        <p class="success"></p>
      </div>
    </form>
  </div>

  <script src="scripts/login.js"></script>


</body>

</html>