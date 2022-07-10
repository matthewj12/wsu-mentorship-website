<?php

    // include 'includes/participant.inc.php';
    include '../includes/functions.php';

    session_start();

            //initialize variables

    $active = $category = $firstname = $lastname = $starID = $gender = $major = $preProgram = $religion = $internationalStudent = $studentAthlete = $multilingual = $notUSborn = $firstGen = $lgbtq = $transferStudent = $undecided = $diversityInterest = $secondLanguages = $races = $hobbies = $preferredGender = $preferredReligion = $preferredRace = $firstQuality = $secondQuality = $thirdQuality = $miscInfo = null;
    //if the form is submitted
    if(isset($_POST['submit_survey']))
    {

        //get the input and assign them to variables and session variables
        $active = $_POST['active'];
        $category =  $_POST["student_category"];
        $firstname = $_POST["fname"];
        $lastname = $_POST["lname"];
        $starID = $_POST["starid"];
        $gender = $_POST["gender"];
        $major = $_POST["major"];
        $preProgram = $_POST["preProgram"];
        $religion = $_POST["menteeReligion"];
        
        //Other factors - boolean values
        //international student post
        if(isset($_POST["internationalStudent"]))
        {
            // $internationalStudent = $_POST["international student"]; 
            $internationalStudent = true;

        }
        else
        {
            $internationalStudent = false;
        }
        
        //student athlete post 
        if(isset($_POST["athlete"]))
        {
            // $studentAthlete = $_POST["student athlete"];
            $studentAthlete = true;
        }
        else
        {
            $studentAthlete = false;
        }

        //multiligual post
        if(isset($_POST["multilingual"]))
        {
            // $multilingual = $_POST["multilingual"];
            $multilingual = true;

        }
        else
        {
            $multilingual = false;
        }

        //not us born post
        if(isset($_POST["notUSborn"]))
        {
            // $notUSborn = $_POST["not born in this country"];
            $notUSborn = true;
        }
        else
        {
            $notUSborn = false;
        }

        //first gen post
        if(isset($_POST["firstGen"]))
        {
            // $firstGen = $_POST["firstgen_student"];
            $firstGen = true;
        }
        else
        {
            $firstGen = false;
        }

        //lgbtq post
        if(isset($_POST["lgbtq+"]))
        {
            // $lgbtq = $_POST["lgbtq+"];
            $lgbtq = true;
        }
        else
        {
            $lgbtq = false;
        }

        //transfer student post
        if(isset($_POST["transferStudent"]))
        {
            // $transferStudent= $_POST["transfer student"];
            $transferStudent = true;
        }
        else
        {
            $transferStudent = false;
        }

        //undecided post
        if(isset($_POST["undecided"]))
        {
            // $undecided = $_POST["undecided"];
            $undecided = true;

        }
        else
        {
            $undecided = false;
        }

        $diversityInterest = $_POST["diversityInterest"];

        //Store in array
        if(isset($_POST["secondLanguages"]))
        {
            
            // $secondLanguages = $_POST['secondLanguages']; 
            $secondLanguages = converArrtoStr($_POST['secondLanguages']);
        }
        if(isset($_POST["races"]))
        {
            // $races = $_POST["races"];
            $races = converArrtoStr($_POST["races"]);
        }
        if(isset($_POST["hobbies"]))
        {
            // $hobbies = $_POST["hobbies"];
            $hobbies = converArrtoStr($_POST["hobbies"]);
        }

        if(isset($_POST["mentorGender"]))
        {
            $preferredGender = $_POST['mentorGender'];
        }

        if(isset($_POST["mentorRace"]))
        {
            $preferredRace = $_POST['mentorRace'];
        }

        if(isset($_POST["mentorReligion"]))
        {
            $preferredReligion = $_POST['mentorReligion'];
        }

        if(isset($_POST["firstQuality"]))
        {
            $firstQuality = $_POST["firstQuality"];
        }

        if(isset($_POST["secondQuality"]))
        {
            $secondQuality = $_POST["secondQuality"];
        }

        if(isset($_POST["thirdQuality"]))
        {
            $thirdQuality = $_POST["thirdQuality"];
        }

        if(isset($_POST["misc_info"]))
        {
            $miscInfo = $_POST["misc_info"];
        }

        echo 'Active?: '. $active;
        echo 'Category: '. $category;
        echo "<br>";
        echo 'First Name: '. $firstname;
        echo "<br>";
        echo 'Last Name: '. $lastname;
        echo "<br>";
        echo 'Warrior ID: '. $starID;
        echo "<br>";
        echo 'Gender: '. $gender;
        echo "<br>";
        echo 'Major: '. $major;
        echo "<br>";
        echo 'Pre Program: '. $preProgram;
        echo "<br>";
        echo 'Religion: '. $religion;
        if($internationalStudent == true)
        {
            echo "International Student: true";
        }
        else
        {
            echo "International Student: false";
        }
        echo "<br>";

        if($studentAthlete == true)
        {
            echo "Student Athlete: true";
        }
        else
        {
            echo "Student Athlete: false";
        }
        echo "<br>";


        if($multilingual == true)
        {
            echo "Multilingual: true";
        }
        else
        {
            echo "Multilingual: false";
        }
        echo "<br>";


        if($notUSborn == true)
        {
            echo "Not US born: true";
        }
        else
        {
            echo "Not US born: false";
        }
        echo "<br>";


        if($firstGen == true)
        {
            echo "First Generation Student: true";
        }
        else
        {
            echo "First Generation Student: false";
        }
        echo "<br>";

        
        if($lgbtq == true)
        {
            echo "LGBTQ: true";
        }
        else
        {
            echo "LGBTQ: false";
        }
        echo "<br>";

        
        if($transferStudent == true)
        {
            echo "Transfer Student: true";
        }
        else
        {
            echo "Transfer Student: false";
        }
        echo "<br>";

        
        if($undecided == true)
        {
            echo "Major undecided: true";
        }
        else
        {
            echo "Major undecided: false";
        }
        echo "<br>";

        echo "Diversity Groups Interested?: ". $diversityInterest;
        echo "<br>";
        
        echo "Diversity Groups Interested?: ". $diversityInterest;
        echo "<br>";
        echo "Second secondLanguages: ";

            print_r($secondLanguages);
            echo "<br>";

            echo converArrtoStr($secondLanguages);
            

        echo "Races: ";

        print_r($races);
            echo "<br>";
            echo converArrtoStr($races);


        echo "Hobbies: ";
        print_r($hobbies);
            echo "<br>";
            echo converArrtoStr($hobbies);


        echo "Mentor Preference";
        echo "<br>";
        echo "mentorGender?: ". $preferredGender;
        echo "<br>";
        echo "mentorRace?: ". $preferredRace;
        echo "<br>";
        echo "1st quality: ". $firstQuality;
        echo "<br>";
        echo "2nd quality: ". $secondQuality;
        echo "<br>";
        echo "thirdQuality: ". $thirdQuality;
        echo "<br>";
        echo "Miscellaneous Information: ". $miscInfo;
        echo "<br>";


    }

    else
    {
        echo "Form Submission Failed";
    }

    $participant = new ParticipantContr($active, $category, $firstname, $lastname, $starID, $gender, $major, $preProgram, $religion, $internationalStudent, $athlete, $multilingual, $notUSborn, $firstGen, $lgbtq, $transferStudent, $undecided, $diversityInterest, $secondLanguages, $races, $hobbies, $mentorGender, $mentorRace,$mentorReligion, $firstQuality, $secondQuality, $thirdQuality, $miscInfo);

    