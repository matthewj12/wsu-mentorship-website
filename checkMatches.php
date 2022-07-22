<?php
require_once('includes/autoloader.inc.php');
require_once('includes/functions.inc.php');

session_start();

//initialize variables
$matchArr = array('mentor' => null, 'mentee' => null);

if(isset($_POST["searchMatch"]))
{
    // $mentorStarID = trim($_POST["mentorStarID"]);
    // $menteeStarID = trim($_POST["menteeStarID"]);
    // $matchArr["mentor"] = trim($_POST["mentorStarID"]);
    // $matchArr["mentee"] = trim($_POST["menteeStarID"]);
    // print_r($matchArr);
    // $matchJSON = json_encode($matchArr);
    // echo $matchJSON;
    if($_POST["mentorStarID"] != null)
    {
        echo $_POST["mentorStarID"];
    }

    else
    {
        echo "Empty!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/checkMatches.css">

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

        <!--Divider-->
        <hr>

        <section>
            <!--Show all the matches in the table-->
            <?php
            $matches = selectMentorship();
            // var_dump($matches);
            ?>

            <h3>Search all matches:</h3>

            <table class="matches">
                <tr>
                    <th>Mentor StarID</th>
                    <th>Mentee StarID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th></th>
                </tr>
                <?php
                $matchArr = null;
                foreach ($matches as $match) {
                    echo "<tr>";
                    echo "<td>" . $match['mentor starid'] . "</td>";
                    echo "<td>" . $match['mentee starid'] . "</td>";
                    echo "<td>" . $match['start date'] . "</td>";
                    echo "<td>" . $match['end date'] . "</td>";
                    echo "<td>" ?>
                    <form method="post" action="">
                    <input type="submit" name="editMatch" value="Edit This Match">
                    </form>                    
                    <?php ;
                    echo "</tr>";
                    if(isset($_POST["editMatch"]))
                    {
                    echo "this works";
                    // $matchArr = array('mentor' => $match['mentor starid'], 'mentee' => $match['mentee starid']);
                    // print_r($matchArr);
                    // $matchJSON = json_encode($matchArr);
                    // echo $matchJSON;
                    }

                    else
                    {
                        echo "this does not work";
                    }
                }
                

                            ?>
            </table>
            </section>
    </main>
</body>

</html>