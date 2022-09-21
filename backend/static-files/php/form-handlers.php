<?php

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



