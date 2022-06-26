<?php


//PDO way
class dbh
{
    private $serverName = "localhost";
    private $dbUsername = "root";
    private $dbPassword = "Sql783knui1-1l;/klaa-9";
    private $dbName = "mp";

    //constructor
    protected function connect()
    {
        try
        {
            $dsn = 'mysql:host='.$this->serverName.';dbname='.$this->dbName;
            $pdo = new PDO($dsn, $this->dbUsername, $this->dbPassword);
            //setting fetch mode
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            //setting error mode
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connection Success";
            return $pdo;
        }
        catch(PDOException $e)
        {
            echo "Connection Failed:". $e->getMessage();
        }
    }

    //select one user or by certain property
    protected function selectUser()
    {

    }

    protected function insertToParticipant($active, $category, $firstname, $lastname, $warrior_id, $gender, $major, $pre_programs, $religion, $internationalStudent, $lgbtq, $studentAthlete, $multilingual, $notUSborn, $transferStudent, $firstGen, $undecided, $interestDiversity, $mentorGender, $mentorRace,$mentorReligion, $firstQuality, $secondQuality, $thirdQuality, $miscInfo)
    {
        try
        {
            $sql = 'INSERT INTO `participant` (`is active`, `is mentor`,`first name`, `last name`, `starid`, `gender`, `major`, `pre program`,`religious affiliation`,`international student`, `lgbtq+`,`student athlete`, `multilingual`,`not born in this country`,`transfer student`,`first gen college student`, `unsure or undecided about major`,`interested in diversity groups`, `preferred gender`, `preferred race`, `preferred religious affiliation` ,
            `1st most important quality`, `2nd most important quality`, `3rd most important quality`, `misc info`)
            values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?,?,?,?,?,?,?)';
            $stmt = $this->connect()->prepare($sql);
            //first ? is the array[0] in execute method aka chronological
            $stmt->execute([$active, $category, $firstname, $lastname, $warrior_id, $gender, $major, $pre_programs, $religion, $internationalStudent, $lgbtq, $studentAthlete, $multilingual, $notUSborn, $transferStudent, $firstGen, $undecided, $interestDiversity, $mentorGender, $mentorRace,$mentorReligion, $firstQuality, $secondQuality, $thirdQuality, $miscInfo]);
            echo "New record added to <h3>Participant</h3> successfully";
        }
        catch(PDOException $e)
        {
            echo "New record addition to <h3>Participant</h3> FAILED";
            echo $sql. $e->getMessage();
        }
    }

    protected function insertToLanguages($starid, $languagesArr)
    {
        foreach($languagesArr as $language)
        {
            try
            {
                $sql = 'INSERT INTO `speaks second languages` (`starid`, `second languages`)
                values (?, ?)';
                // $sql = 'INSERT INTO participant(users_firstname,users_lastname, users_bd) values(?, ?, ?)';
                $stmt = $this->connect()->prepare($sql);
                //first ? is the array[0] in execute method aka chronological
                $stmt->execute([$starid, $language]);
                echo "New record added to <h3>Second Languages</h3> successfully";
            }
            catch(PDOException $e)
            {
                echo "New record addition to <h3>Second Languages</h3> FAILED";
                echo $sql. $e->getMessage();
            }
        }

        
    }

    protected function insertToHobby($starid, $hobbiesArr)
    {
        foreach($hobbiesArr as $hobby)
            {
            try
            {
                $sql = 'INSERT INTO `hasHobby` (`starid`, `hobby`)
                values (?, ?)';
                // $sql = 'INSERT INTO participant(users_firstname,users_lastname, users_bd) values(?, ?, ?)';
                $stmt = $this->connect()->prepare($sql);
                //first ? is the array[0] in execute method aka chronological
                $stmt->execute([$starid, $hobby]);
                echo "New record added to <h3>Has Hobby</h3> successfully";
            }
            catch(PDOException $e)
            {
                echo "New record addition to <h3>Has Hobby</h3> FAILED";
                echo $sql. $e->getMessage();
            }
        }
    }

    protected function insertToRace($starid, $raceArr)
    {
        foreach($raceArr as $race)
        {
            try
            {
                $sql = 'INSERT INTO `hasHobby` (`starid`, `hobby`)
                values (?, ?)';
                // $sql = 'INSERT INTO participant(users_firstname,users_lastname, users_bd) values(?, ?, ?)';
                $stmt = $this->connect()->prepare($sql);
                //first ? is the array[0] in execute method aka chronological
                $stmt->execute([$starid, $race]);
                echo "New record added to <h3>Has Hobby</h3> successfully";
            }
            catch(PDOException $e)
            {
                echo "New record addition to <h3>Has Hobby</h3> FAILED";
                echo $sql. $e->getMessage();
            }
        }
    }

    protected function delete()
    {

    }

    protected function showAllMentees()
    {
        //select mentees from database and echo
    }

    protected function showAllMentors()
    {
        //select mentors from database and echo
    }
    


}