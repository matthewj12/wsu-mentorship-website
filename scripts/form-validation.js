
const form = document.getElementById('form');
const emailInput = document.getElementById('email');

form.addEventListener('submit', (e)=>
{
    e.preventDefault();
    validateForm();
});

function validateForm()
{
    //if email input is empty
    if(emailInput.value.trim() === '' || emailInput.value.trim() === null)
    {
        document.querySelector('.error email-error').innerHTML = 'Email should not be blank';
        document.querySelector('.error email-error').style.display = 'block';
    }

    //if email input is not empty
    else
    {
        var emailFormat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        //valid email format
        if(inputText.value.match(emailFormat))
        {
            document.querySelector('.success email-success').innerHTML = 'Valid Email';
            document.querySelector('.success email-success').style.display = 'block';

            document.querySelector('.success signIn-success').innerHTML = 'Valid Form';
            document.querySelector('.success signIn-success').style.display = 'block';

            //then redirect using windows.href with json data to the login.inc.php
            setTimeout(function(){
                window.location.href = 'backend\static-files\php\login.inc.php';
             }, 5000);
        }

        //invalid email format
        else
        {
            document.querySelector('.error email-error').innerHTML = 'Invalid Email';
            document.querySelector('.error email-error').style.display = 'block';

            document.querySelector('.error signIn-error').innerHTML = 'Valid Form';
            document.querySelector('.error signIn-error').style.display = 'block';
        }

    }

}


