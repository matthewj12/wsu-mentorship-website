<?php

//Form Error Handlers
    
//check Error Input
function checkEmpty($attribute)
{
    $result = "";
    if(empty($attribute))
    {
        $result = true;
    }

    else
    {
        $result = false;
    }

    return $result;
}


//check Email Format - it has to be starid@go.winona.edu
//use regex or other filter_var or use arrays and use explode
function checkEmail($email)
{

    $result = "";
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        // header("location:register.php");
        // header("location:/MIYA-OOP/index.php?error=invalidemail");
        // exit("This email is not a valid email");
        $result = false;
    }

    else
    {
        $result = true;
    }
    
    return $result;
}


//check pregmatch - String attributes 
function checkvalidString($string)
{
    $result = "";

    if(!preg_match('/^[a-zA-Z0-9_]*$/',$string))
    {
        $result = false;
    }

    else
    {
        $result = true;
    }

    return $result;
}



