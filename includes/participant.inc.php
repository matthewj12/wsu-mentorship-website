<?php

    include 'includes/autoloader.inc.php';
    include 'includes/functions.class.php';

    session_start();

    //if the form is submitted
    if(isset($_POST['submit']))
    {
        //get the input and assign them to variables and session variables
        $category =  $_POST["student_category"];
        $firstname = $_POST["fname"];
        $lastname = $_POST["lname"];
        $warriorId = $_POST["warrior_id"];
        $gender = $_POST["gender"];
        $major = $_POST["primary_major"];
        $pre_programs = $_POST["pre_programs"];
        $religion = $_POST["religion"];

        //Other factors - boolean values
        if(isset($_POST["international_student"]))
        {
            $internationalStudent = $_POST["international_student"]; 
        }
        if(isset($_POST["student_athlete"]))
        {
            $studentAthlete = $_POST["student_athlete"];
        }
        if(isset($_POST["multilingual"]))
        {
            $multilingual = $_POST["multilingual"];
        }
        if(isset($_POST["not_born_in_US"]))
        {
            $notUSborn = $_POST["not_born_in_US"];
        }
        if(isset($_POST["firstgen_student"]))
        {
            $firstGen = $_POST["firstgen_student"];
        }
        if(isset($_POST["lgbtq+"]))
        {
            $lgbtq = $_POST["lgbtq+"];
        }
        if(isset($_POST["transfer_student"]))
        {
            $transferStudent= $_POST["transfer_student"];
        }
        if(isset($_POST["undecided"]))
        {
            $undecided = $_POST["undecided"];
        }

        
        $interestDiversity = $_POST["interest_diversity"];

        //Store in array
        if(isset($_POST["languages[]"]))
        {
            $languagesArr = $_POST['languages[]'];  
        }
        if(isset($_POST["ethnicity[]"]))
        {
            $ethnicityArr = $_POST["ethnicity[]"];
        }
        if(isset($_POST["hobbies[]"]))
        {
            $hobbiesArr = $_POST["hobbies[]"];
        }

        $mentorGender = $_POST["mentor_gender"];
        $mentorRace = $_POST["mentor_race"];
        $mentorReligion = $_POST["mentor_religion"];
        $firstQuality = $_POST["1st quality"];
        $secondQuality = $_POST["2nd quality"];
        $thirdQuality = $_POST["3rd quality"];
        $miscInfo = $_POST["misc_info"];

        //Create ParticipantContr object with the variables above

        
        //call ParticipantContr.register to insert all information

        //Craete ParticipantView object to show the mentees registered in a table
        //Or
        //include the participantView file in participants
    }