var emailElement = document.getElementById("email");
var emailValue = emailElement.value;
let errorMessages = [];
let successMessages = [];
document.querySelector(".error").innerHTML = "";
document.querySelector(".success").innerHTML = "";
document.querySelector(".error").style.display = "none";
document.querySelector(".success").style.display = "none";

window.onload = function () {
  if (emailElement) {
    emailElement.addEventListener("keyup", checkInput);
  }
};

form.addEventListener("submit", function (e) {
  // prevent the form from submitting
  e.preventDefault();
  if (errorMessages === undefined || errorMessages.length == 0) {
    console.log('There is no errors!');
    setTimeout(function () {
      window.location.href = "backend/static-files/php/login.inc.php?email=" + emailElement.value;
    }, 5000);
  } else {
    e.preventDefault();
  }
});

function checkInput() {
    //input is empty
  if (emailElement.value.trim() == "") {
    
    document.querySelector(".error").innerHTML = "Empty Input";
    document.querySelector(".error").style.display = "block";
    console.log(emailElement.value);
    document.querySelector(".success").innerHTML = "";
    document.querySelector(".success").style.display = "none";
    errorMessages.push(document.querySelector(".error").innerHTML);
    //email is not empty
  } else {
    checkEmail();
    console.log(emailElement.value);
  }

  console.log(checkEmail());
}

function checkEmail() {
  const pattern =
    /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  if (pattern.test(emailElement.value.trim())) {
    document.querySelector(".error").innerHTML = "";
    document.querySelector(".error").style.display = "none";
    document.querySelector(".success").innerHTML = "Valid Email";
    document.querySelector(".success").style.display = "block";
    successMessages.push(document.querySelector(".success".innerHTML));
    errorMessages = [];
    console.log(emailElement.value);

  } else {
    document.querySelector(".error").innerHTML = "Invalid Email";
    document.querySelector(".error").style.display = "block";
    console.log(emailElement.value);
    document.querySelector(".success").innerHTML = "";
    document.querySelector(".success").style.display = "none";
    errorMessages.push(document.querySelector(".error").innerHTML);
    successMessages = [];
  }

  return pattern.test(emailElement.value.trim());
}
