<?php
    require_once('includes/autoloader.inc.php');
    require_once('includes/functions.inc.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Matches</title>
</head>
<body>
    <main>
    <section>
            <h3>Search by each pair:</h3>
            <form action = "" method = "post">
                <input type = "text" name = "mentorStarID" placeholder = "Mentor StarID">
                <input type = "text" name = "menteeStarID" placeholder = "Mentee StarID">
                <input type = "submit" name = "searchMatch" value = "Search Match">

            </form>
        </section>
    </main>
</body>
</html>