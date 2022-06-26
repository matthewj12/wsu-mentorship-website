<?php

//This is model class where we will perform queries from and to database
//This class will not interact with client directly
//This class will interact with participantcontr only
    class Participant extends Dbh
    {
        //Properties of a participant
        //Posted information from client
        //27 Properties
        protected $active;
        protected $category;
        protected $firstName;
        protected $lastName;
        protected $warrior_id;
        protected $gender;
        protected $major;
        protected $pre_programs;
        protected $religion;
        protected $internationalStudent;
        protected $studentAthlete;
        protected $multilingual;
        protected $notUSborn;
        protected $firstGen;
        protected $lgbtq;
        protected $transferStudent;
        protected $undecided;
        protected $interestDiversity;
        protected $languagesArr;
        protected $ethnicityArr;
        protected $hobbiesArr;
        protected $mentorGender;
        protected $mentorRace;
        protected $mentorReligion;
        protected $firstQuality;
        protected $secondQuality;
        protected $thirdQuality;
        protected $miscInfo;


    //functions
    //constructor
    //One problem here for the boolean values for transfer student etc
    public function __construct($active, $category, $firstname, $lastname, $warrior_id, $gender, $major, $pre_programs, $religion, $internationalStudent, $studentAthlete, $multilingual, $notUSborn, $firstGen, $lgbtq, $transferStudent, $undecided, $interestDiversity, $languagesArr, $ethnicityArr, $hobbiesArr, $mentorGender, $mentorRace,$mentorReligion, $firstQuality, $secondQuality, $thirdQuality, $miscInfo) 
    {
        $this->active = $active;
        $this->category = $category;
        $this->starid = $warrior_id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->gender = $gender;
        $this->major = $major;
        $this->pre_programs = $pre_programs;
        $this->religion = $religion;
        $this->internationalStudent = $internationalStudent;
        $this->studentAthlete = $studentAthlete;
        $this->internationalStudent = $internationalStudent;
        $this->multiligual = $multilingual;
        $this->notUSborn = $notUSborn;
        $this->firstGen = $firstGen;
        $this->lgbtq = $lgbtq;
        $this->transferStudent = $transferStudent;
        $this->undecided = $undecided;
        $this->interestDiversity = $interestDiversity;
        $this->ethnicityArr = $ethnicityArr;
        $this->hobbiesArr = $hobbiesArr;
        $this->languagesArr = $languagesArr;
        $this->mentorGender = $mentorGender;
        $this->mentorRace = $mentorRace;
        $this->mentorReligion = $mentorReligion;
        $this->firstQuality = $firstQuality;
        $this->secondQuality = $secondQuality;
        $this->thirdQuality = $thirdQuality;
        $this->miscInfo = $miscInfo;

        
    }

    protected function register()
    {

        //call the private setters (Hobbies, Race, Languages) here and mark it as register user one main method

        
    }

    private function setParticipant()
    {
        // //add information to Participants table
        // $this->insertToParticipant($this->category, $this->firstname, $this->lastname, $this->starid, $this->gender, $this->major, $this->pre_programs, $this->internationalStudent, );
        // $sql = "INSERT INTO users(users_firstname,users_lastname, users_bd) values(?, ?, ?)";
        // $stmt = $this->connect()->prepare($sql);
        // //first ? is the array[0] in execute method aka chronological
        // $stmt->execute([$firstname,$lastname, $dob]);
        
    }

    private function setHobbies()
    {
        //add information to hasHobbies table
    }

    private function setRace()
    {
        //add information to hasHobbies table
    }

    private function setLanguages()
    {
        //add information to hasHobbies table
    }

}
    $student_category = "";
    $firstname = "";
    $lastname = "";
    $warrior_id = "";
    $gender = "";
    $primary_major = "";
    $pre_programs = "";
    $international_student = "";
    $transfer_student = "";
    $first_gen = "";
    $not_born_US = "";
    $lgbtq = "";
    $student_athlete = "";
    $multilingual = "";
    $undecided = "";
    $assoc_dept = "";
    $languages = "";
    if(isset($_POST["submit_survey"]))
    {
        echo "Form submitted successfully";

        if(isset($_POST['student_category']))
        {
            echo "This participant is a ". $_POST["student_category"];
            $student_category = $_POST["student_category"];
        }

        if(isset($_POST["fname"]))
        {
            echo "First name is ". $_POST["fname"];
            $firstname = $_POST["fname"];
        }

        if(isset($_POST["lname"]))
        {
            echo "Last name is ". $_POST["lname"];
            $lastname = $_POST["lname"];
        }

        if(isset($_POST["warrior_id"]))
        {
            echo "Warrior ID is ". $_POST["warrior_id"];
            $warrior_id = $_POST["warrior_id"];
        }

        if(isset($_POST["gender"]))
        {
            echo "Chosen gender: ". $_POST['gender'] ;
            $gender = $_POST['gender'];  
            
            // $chk="";  
            // foreach($radio1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     }  
            // $gender = $chk;
            // echo "Chosen gender: ". $chk;
        }

        if(isset($_POST["primary_major"]))
        {
            echo "Chosen major is ". $_POST["primary_major"];
            $primary_major = $_POST["primary_major"];
        }

        if(isset($_POST["pre_programs"]))
        {
            echo "Chosen pre-program : ". $_POST['pre_programs'] ;
            $pre_programs = $_POST['pre_programs'];  
            // $radio1=$_POST['pre_programs'];  
            // $chk="";  
            // foreach($radio1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     }  
            
            // $pre_programs = $chk;
            
            // echo "Chosen programs: ". $chk;
        }

        echo "Hello";
        if(isset($_POST["international_student"]))
        {
            echo "This student is an international student : (1 for true (OR) 0 for false )". $_POST['international_student'] ;
            $international_student = $_POST['international_student']; 
            // $checkbox1=$_POST['category[]'];  
            // $chk="";  
            // foreach($checkbox1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     } 
            // $categories = $chk; 
            
            // echo "Chosen categories are : ". $chk;
        }

        if(isset($_POST["student_athlete"]))
        {
            echo "Hello";
            echo "This student is an athlete : (1 for true (OR) 0 for false )". $_POST["student_athlete"] ;
            $student_athlete = $_POST["student_athlete"]; 
            // $checkbox1=$_POST['category[]'];  
            // $chk="";  
            // foreach($checkbox1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     } 
            // $categories = $chk; 
            
            // echo "Chosen categories are : ". $chk;
        }

        if(isset($_POST["multilingual"]))
        {
            echo "This student is multilingual: (1 for true (OR) 0 for false )". $_POST["multilingual"] ;
            $multilingual = $_POST["multilingual"]; 
            // $checkbox1=$_POST['category[]'];  
            // $chk="";  
            // foreach($checkbox1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     } 
            // $categories = $chk; 
            
            // echo "Chosen categories are : ". $chk;
        }

        if(isset($_POST["not_born_in_US"]))
        {
            echo "This student is not born in US: (1 for true (OR) 0 for false )". $_POST["not_born_in_US"] ;
            $not_born_US = $_POST["not_born_in_US"]; 
            // $checkbox1=$_POST['category[]'];  
            // $chk="";  
            // foreach($checkbox1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     } 
            // $categories = $chk; 
            
            // echo "Chosen categories are : ". $chk;
        }

        if(isset($_POST["firstgen_student"]))
        {
            echo "This student is first gen student: (1 for true (OR) 0 for false )". $_POST["firstgen_student"] ;
            $first_gen = $_POST["firstgen_student"]; 
            // $checkbox1=$_POST['category[]'];  
            // $chk="";  
            // foreach($checkbox1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     } 
            // $categories = $chk; 
            
            // echo "Chosen categories are : ". $chk;
        }

        if(isset($_POST["lgbtq+"]))
        {
            echo "This student is part of lgbtq+ community: (1 for true (OR) 0 for false )". $_POST["lgbtq+"] ;
            $lgbtq = $_POST["lgbtq+"]; 
            // $checkbox1=$_POST['category[]'];  
            // $chk="";  
            // foreach($checkbox1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     } 
            // $categories = $chk; 
            
            // echo "Chosen categories are : ". $chk;
        }

        if(isset($_POST["transfer_student"]))
        {
            echo "This student is a transfer student: (1 for true (OR) 0 for false )". $_POST["transfer_student"] ;
            $transfer_student = $_POST["transfer_student"]; 
            // $checkbox1=$_POST['category[]'];  
            // $chk="";  
            // foreach($checkbox1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     } 
            // $categories = $chk; 
            
            // echo "Chosen categories are : ". $chk;
        }

        if(isset($_POST["undecided"]))
        {
            echo "This student is undecided: (1 for true (OR) 0 for false )". $_POST["undecided"] ;
            $undecided = $_POST["undecided"]; 
            // $checkbox1=$_POST['category[]'];  
            // $chk="";  
            // foreach($checkbox1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     } 
            // $categories = $chk; 
            
            // echo "Chosen categories are : ". $chk;
        }

        if(isset($_POST["assoc_dept"]))
        {
            echo "Chosen pre-program : ". $_POST['assoc_dept'] ;
            $assoc_dept = $_POST['assoc_dept'];  
            // $radio1=$_POST['pre_programs'];  
            // $chk="";  
            // foreach($radio1 as $chk1)  
            //     {  
            //         $chk .= $chk1.",";  
            //     }  
            
            // $pre_programs = $chk;
            
            // echo "Chosen programs: ". $chk;
        }

        // if(isset($_POST["assoc_dept"]))
        // {
        //     $radio1=$_POST['assoc_dept'];  
        //     $chk="";  
        //     foreach($radio1 as $chk1)  
        //         {  
        //             $chk .= $chk1.",";  
        //         }  
            
        //     $assoc_dept = $chk;
        //     echo "Chosen associations are : ". $chk;
        // }

        // if(isset($_POST["languages"]))
        // {
        //     $radio1=$_POST['languages'];  
        //     $chk="";  
        //     foreach($radio1 as $chk1)  
        //         {  
        //             $chk .= $chk1.",";  
        //         }  
        //     $languages = $chk;
            
        //     echo "Chosen languages are : ". $chk;

        // }

        // if(isset($_POST["ethnicity"]))
        // {
        //     $radio1=$_POST['ethnicity'];  
        //     $chk="";  
        //     foreach($radio1 as $chk1)  
        //         {  
        //             $chk .= $chk1.",";  
        //         }  
            
        //     $ethnicity = $chk;
            
        //     echo "Chosen ethnicities are : ". $chk;
        // }

        // if(isset($_POST["hobbies"]))
        // {
        //     $radio1=$_POST['hobbies'];  
        //     $chk="";  
        //     foreach($radio1 as $chk1)  
        //         {  
        //             $chk .= $chk1.",";  
        //         }  
            
        //     $hobbies = $chk;
        //     echo "Chosen hobbies are : ". $chk;
        // }


        // if(isset($_POST["mentor_gender"]))
        // {
        //     $radio1=$_POST['mentor_gender'];  
        //     $chk="";  
        //     foreach($radio1 as $chk1)  
        //         {  
        //             $chk .= $chk1.",";  
        //         }  
        //     $mentor_gender = $chk;
        //     echo "Prefereed mentor's genders are : ". $chk;
        // }
        
        // if(isset($_POST["match"]))
        // {
        //     $radio1=$_POST['match'];  
        //     $chk="";  
        //     foreach($radio1 as $chk1)  
        //         {  
        //             $chk .= $chk1.",";  
        //         }  
        //     $match = $chk;
        //     echo "Match conditions are : ". $chk;
        // }

        // if(isset($_POST["other_comments"]))
        // {
        //     $other_comments = $_POST["other_comments"];
            
        //     echo "Comment: ". $other_comments;
        // }

        // function insert_participant(){

        // };

        // function insert_participant_1()
        // {

        // };
        // $submit_time = date("Y/m/d g:i:s A");
        // $sql = "INSERT INTO mentee_survey (fname,lname,submit_time, warrior_id, gender, primary_major, pre_program, student_category, asoc_dpt, languages, ethnicity, hobbies, m_gender_pref, m_category_pref, others )
        // VALUES ('$firstname', '$lastname','$submit_time', '$warrior_id','$gender', '$primary_major','$pre_programs', '$categories', '$assoc_dept','$languages', '$ethnicity', '$hobbies', '$mentor_gender', '$match', '$other_comments')";

        // if(mysqli_query($conn,$sql)){
        //     echo "New record has been added successfully";
        //     session_start();
        // }

        // else{
        //     echo"Error:".$sql. ":-".mysqli_error($conn);
        //     exit("Data addition unsuccessful");
        //     die;
        // }
        
    }

?>