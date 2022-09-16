var verificationElement = document.getElementById("code");
// var verificationCode = verificationElement.value;
var formElement = document.getElementById("verifySignIn");
let errorMessages = [];
let successMessages = [];
document.querySelector(".error").innerHTML = "";
document.querySelector(".success").innerHTML = "";
document.querySelector(".error").style.display = "none";
document.querySelector(".success").style.display = "none";

window.onload = function () {
  if (verificationElement) {
    verificationElement.addEventListener("keyup", checkInput);
  }
};


formElement.addEventListener("submit", function (e) {
  // prevent the form from submitting
  e.preventDefault();
  if (errorMessages === undefined || errorMessages.length == 0) {
    console.log("There is no errors!");
    var verificationCode = verificationElement.value;
    console.log(verificationCode);
    console.log(typeof(verificationCode));
    // var codeHash = md5(verificationElement.value);
    setTimeout(function () {
      window.location.href = "backend/static-files/php/email-verify.inc.php?verification="+ verificationCode;
    }, 5000);
  } else {
    console.log("There are errors!!!!!!!!!!1");
    e.preventDefault();
  }
});

function checkInput() {
  //input is empty
  if (verificationElement.value.trim() == "") {
    document.querySelector(".error").innerHTML = "Empty Input";
    document.querySelector(".error").style.display = "block";
    console.log(verificationElement.value);
    document.querySelector(".success").innerHTML = "";
    document.querySelector(".success").style.display = "none";
    errorMessages.push(document.querySelector(".error").innerHTML);
    //email is not empty
  } else {
    checkVerificationCode();
    console.log(verificationElement.value);
  }

  console.log(checkVerificationCode());
}


function checkVerificationCode() {
  //check 6 numbers length
  if (verificationElement.value.length < 6 || verificationElement.value.length > 6) {

    document.querySelector(".error").innerHTML =
      "Verification Code is under or over required count";
    document.querySelector(".error").style.display = "block";
    document.querySelector(".success").innerHTML = "";
    document.querySelector(".success").style.display = "none";
    errorMessages.push(document.querySelector(".error").innerHTML);
    successMessages = [];
    console.log(verificationElement.value);
    console.log(verificationElement.value.length);
  }

  //if verificaiton code is exactly 6 counts
  else if(verificationElement.value.length === 6 ) {
    console.log(verificationElement.value);
    console.log(verificationElement.value.length);
    document.querySelector(".error").innerHTML = "";
    document.querySelector(".error").style.display = "none";
    document.querySelector(".success").innerHTML = "Valid Verification Code";
    document.querySelector(".success").style.display = "block";
    successMessages.push(document.querySelector(".success".innerHTML));
    errorMessages = [];
  }
}

// function checkCodePattern() {
//   //check numbers only
//   const codePattern = /^[0-9]+$/;
//   if (verificationElement.value.trim().match(codePattern)) {
//     console.log('Code pattern matches');
//     console.log(verificationElement.value);
//     document.querySelector(".error").innerHTML = "";
//     document.querySelector(".error").style.display = "none";
//     document.querySelector(".success").innerHTML = "Valid Verification Code";
//     document.querySelector(".success").style.display = "block";
//     successMessages.push(document.querySelector(".success".innerHTML));
//     errorMessages = [];
//   } else {
//     console.log('Code pattern does not match');
//     console.log(verificationElement.value);
//     document.querySelector(".error").innerHTML = "Invalid Verification Code";
//     document.querySelector(".error").style.display = "block";
//     document.querySelector(".success").innerHTML = "";
//     document.querySelector(".success").style.display = "none";
//     errorMessages.push(document.querySelector(".error").innerHTML);
//     successMessages = [];
//   }

//   return codePattern.test(verificationElement.value.trim());
// }
