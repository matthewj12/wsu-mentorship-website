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
    <title>Document</title>
    <link rel="stylesheet" href="styles/checkMatches.css">
</head>
<body>
    <main>
        <div>
            <!--Show all the matches in the table-->
            <?php

                GLOBAL $matches;
                $matches = selectMentorship();
                // var_dump($matches);
            ?>

            <h3>All Matches</h3>

            <table class = "matches">
                <tr>
                    <th>Mentor StarID</th>
                    <th>Mentee StarID</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th></th>
                </tr>
                <?php
                    foreach($matches as $match)
                    {
                        echo "<tr>";
                        echo "<td>".$match['mentor starid']."</td>";
                        echo "<td>".$match['mentee starid']."</td>";
                        echo "<td>".$match['start date']."</td>";
                        echo "<td>".$match['end date']."</td>";
                        echo "<td>"?><button>Edit</button> <?php ;
                        echo "</tr>";
                    }
                ?>
            </table>
        </div>
    </main>
</body>
</html>