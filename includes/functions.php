<?php

    //function to get single choice input from html form
    //return array
    function assignSingle($inputField)
    {
        if(isset($_POST['$inputField']))
        {
            echo "This participant is a ". $_POST['$inputField'];
            $inputField = $_POST[$inputField];
        }

        echo $inputField;
    }

    //function to get multiple choice information from html form 
    //and return array
    function assignStrArray($inputField)
    {
        $inputList=$_POST['$inputField'];  
        $chk="";  
        foreach($inputList as $chk1)  
            {  
                $chk .= $chk1.",";  
            }  
        
        $assoc_dept = $chk;
        echo "Chosen associations are : ". $chk;
        return $inputList;
    }

    //function to get boolean array from html form
    //e.g. comfortable sharing
    //maybe like use case switchas
    function assignBooleanArray($inputField)
    {
        if(isset($_POST['$inputField']))
        {
        }
        return $inputField;
    }