const form  = document.getElementById('signUp');
const firstName = form.elements['firstName'];
const lastName = form.elements['lastName'];
const starId = form.elements['starID'];
const password = form.elements['password'];

let fName = firstName.value;
let lName = lastName.value;
let starID = starId.value;
let pwd = password.value;

form.addEventListener('submit', (e) => {
    // handle the form data
    e.preventDefault();
});