<?php
    declare(strict_types = 1);
    include 'includes/autoloader.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>MIYA Food Ordering App</title>
    <meta charset="UTF-8">
    <meta name="author" content="Ei Myatnoe Aung">
    <meta name="keywords" content="Japanese food, MIYA, food order"> 
    <meta name="description" content="MIYA In hourse food ordering website">
    <!-- <meta http-equiv="refresh" content="30"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src="index.js"></script>
<head>
<body>
    <form method = "POST">
        <button class = "button" type = "submit" name = "mentor_btn">
            Click here if you are a mentor
        </button>

        <button class = "button" type = "submit" name = "mentee_btn">
            Click here if you are a mentee
        </button>
    </form>  

    <?php
        if(isset($_POST['mentor_btn']))
            {
                header("location:/Mentor Program/mentorSurvey.php");
                // header("location:/2022/summer/mmp/mentorSurvey.php");

            }
        
        if(isset($_POST['mentee_btn']))
            {                
                header("location:/Mentor Program/menteeSurvey.php");
                // header("location:/2022/summer/mmp/menteeSurvey.php");
                
            }

    ?>

    <?php
        // $testObj = new Test();
        // $testObj->getUsers();
        // $testObj->getUsersStmt("Ei", "Riley");
        // $testObj->setUsersStmt("Minion", "Despicable", "2003-12-11" );

        // $userObj = new UsersView();
        // // $userObj->showUsers("Ei");
        // $usersObj2 = new UsersContr();
        // $usersObj2->createUser("Jane", "Doe", "1989-12-11");
    ?>


</body>
</html>
