<?php

//We are gonna use PDO 
include 'includes/autoloader.inc.php';
require("includes/config.php");
include 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>WSU College of Science and Engineering Mentorship Program</title>
    <meta charset="UTF-8">
    <meta name="author" content="Ei Myatnoe Aung">
    <meta name="keywords" content="Mentor, Mentee, WSU, science">
    <meta name="description" content="Mentor Program Survey website">
    <meta name="viewport" content="width=device-width, initial-scale=" 1">

    <head>

    <body>

        <h1>Hello. Welcome from mentee survey</h1>

        <?php
        $connection = connect();
        ?>
        <form method="post" action="includes/participant.inc.php">

            <label for="student category">Are you active?:</label>
            <select id="student category" name="active">
                <option value="" disabled>--- Select ONE ---</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            <br>
            <!--Student category-->
            <label for="student category">Are you a mentor or a mentee?:</label>
            <select id="student category" name="student_category">
                <option value="" disabled>--- Select ONE ---</option>
                <option value="1">Mentee</option>
                <option value="0">Mentor</option>
            </select>

            <!--First Name-->
            <label for="fname">
                <h3>First Name :</h3>
            </label>
            <input type="text" name="fname" required>
            <br>

            <!--Last Name-->
            <label for="lname">
                <h3>Last Name :</h3>
            </label>
            <input type="text" name="lname" required>
            <br>

            <!--Warrior ID-->
            <label for="starid">
                <h3>StarID : </h3>
            </label>
            <input type="text" name="starid" required>
            <br>

            <!--Gender ( ONLY ONE CHOICE ALLOWED )  Dynamic version-->
            <?php
            try {
            ?>
                <label for="gender">
                    <h3>Gender :</h3>
                </label>
                <select class="form_field" name="gender">
                    <option value="" disabled>--- Select ONE ---</option>
                    <?php
                    $dbName = 'mp';
                    $tableName = 'participant';
                    $columnName = 'gender';
                    $openTag = 'option';
                    $name = 'gender';
                    readEnumValues($openTag, $dbName, $tableName, $columnName, $name);
                    ?>
                </select>
            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            ?>
            </select>

            <!--Primary Major ( ONLY ONE CHOICE ALLOWED )Dynamic version-->
            <?php
            try {
            ?>
                <label for="primary_major">
                    <h3>What is your primary major in the College of Science and Engineering?</h3>
                </label>
                <?php
                $dbName = 'mp';
                $tableName = 'participant';
                $columnName = 'major';
                $openTag = 'radio';
                $name = 'major';
                readEnumValues($openTag, $dbName, $tableName, $columnName, $name);
                ?>
                </select>
            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            ?>

            <!--Professional Program( ONLY ONE CHOICE ALLOWED )Dynamic version-->
            <?php
            try {
            ?>
                <label for="pre program">
                    <h3>What is your professional program in the College of Science and Enginnering?</h3>
                </label>
                <select class="form_field" name="preProgram">
                <?php
                $dbName = 'mp';
                $tableName = 'participant';
                $columnName = 'pre program';
                $openTag = 'radio';
                $name = 'preProgram';
                readEnumValues($openTag, $dbName, $tableName, $columnName, $name);
                ?>
                </select>
            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }

            ?>

            <!--Religion (MULTIPLE CHOICES ALLOWED )Dynamic version-->
            <?php
            try {
            ?>
                <label for="religion">
                    <h3>Are you following any religion?</h3>
                </label>
                <?php
                $dbName = 'mp';
                $tableName = 'participant';
                $columnName = 'religious affiliation';
                $openTag = 'radio';
                $name = 'menteeReligion';
                readEnumValues($openTag, $dbName, $tableName, $columnName, $name);
                ?>
                </select>
            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            ?>


            <!--Student Category individual version-->
            <label for="category">
                <h3>Please check all that you are comfortable sharing.</h3>
            </label>
            <input type="checkbox" name="internationalStudent" value="1">
            <label for="internationalStudent">International student</label>
            <br>
            <input type="checkbox" name="athlete" value="1">
            <label for="athlete">Student athlete</label>
            <br>
            <input type="checkbox" name="multilingual" value="1">
            <label for="multilingual">Speak two or more languages</label>
            <br>
            <input type="checkbox" name="notUSborn" value="1">
            <label for="notUSborn">Not born in this country</label>
            <br>
            <input type="checkbox" name="firstGen" value="1">
            <label for="firstGen">First in my family to attend college</label>
            <br>
            <input type="checkbox" name="lgbtq+" value="1">
            <label for="lgbtq+">LGBTQ+</label>
            <br>
            <input type="checkbox" name="transferStudent" value="1">
            <label for="transferStudent">Transferred from another college</label>
            <br>
            <input type="checkbox" name="undecided" value="1">
            <label for="undecided">Unsure or undecided about my major</label>
            <br>

            <!--Associated Departments-->
            <label for="diversityInterest">
                <h3>Are you or do you plan to associate with any of the following departments?</h3>
            </label>
            International Office <br>
            Office of Equity and Inclusive Excellence<br>
            TRIO<br>
            <br>
            <input type="radio" name="diversityInterest" value="1">
            <label for="diversityInterest">Yes</label>
            <input type="radio" name="diversityInterest" value="0">
            <label for="diversityInterest">No</label>
            <br>

            <!--Dynamic radio list-->
            <label for="secondLanguages">
                <h3>Please identify any languages beyond English that you speak.</h3>
            </label>
            <!--dynamic dropdown from second language table-->
            <?php
            $fieldType = 'checkbox';
            $tableName = 'second language';
            $column = 'second language';
            $name = 'secondLanguages';
            readRefTable($fieldType, $tableName, $column, $name);
            ?>

            <!--Ethnicity/gender-->
            <label for="races">
                <h3>What is your ethnicity/gender? (Select multiple ethnicities if applicable.)</h3>
            </label>
            <!--dynamic dropdown from race table-->
            <?php
            $fieldType = 'checkbox';
            $tableName = 'race';
            $column = 'race';
            $name = 'races';
            readRefTable($fieldType, $tableName, $column, $name);
            ?>

            <!--Dynamic radio list-->
            <label for="hobby[]">
                <h3>Hobbies/Interests: Check all items that are important to you:</h3>
            </label>
            <!--dynamic dropdown from important qualities table-->
            <?php
            $fieldType = 'checkbox';
            $tableName = 'hobby';
            $column = 'hobby';
            $name = 'hobbies';
            readRefTable($fieldType, $tableName, $column, $name);
            ?>

            <!--MENTOR GENDER( ONLY ONE CHOICE ALLOWED )Dynamic version-->
            <?php
            try {
                echo "Success";
            ?>
                <label for="mentorGender">
                    <h3>What is your preferred gender?</h3>
                </label>
                <?php
                $dbName = 'mp';
                $tableName = 'participant';
                $columnName = 'preferred gender';
                $openTag = 'radio';
                $name = 'mentorGender';
                readEnumValues($openTag, $dbName, $tableName, $columnName, $name);
                ?>
                </select>
            <?php
            } catch (PDOException $e) {
                echo "Failed";
                echo $e->getMessage();
            }

            ?>

            <!--MENTOR RACE( ONLY ONE CHOICE ALLOWED )Dynamic version-->
            <?php
            try {
                echo "Success";
            ?>
                <label for="mentorRace">
                    <h3>What is your preferred race for the mentor?</h3>
                </label>
                <?php
                $dbName = 'mp';
                $tableName = 'participant';
                $columnName = 'preferred race';
                $name = 'mentorRace';
                
                $openTag = 'radio';
                readEnumValues($openTag, $dbName, $tableName, $columnName, $name);
                ?>
                </select>
            <?php
            } catch (PDOException $e) {
                echo "Failed";
                echo $e->getMessage();
            }

            ?>

            <!--MENTOR RELIGION( ONLY ONE CHOICE ALLOWED )Dynamic version-->
            <?php
            try {
                echo "Success";
            ?>
                <label for="mentorReligion">
                    <h3>What is your preferred religion for the mentor?</h3>
                </label>
                <?php
                $dbName = 'mp';
                $tableName = 'participant';
                $columnName = 'preferred religious affiliation';
                $openTag = 'radio';
                $name = 'mentorReligion';
                readEnumValues($openTag, $dbName, $tableName, $columnName, $name);
                ?>
                
            <?php
            } catch (PDOException $e) {
                echo "Failed";
                echo $e->getMessage();
            }

            ?>

            <!--Top three qualities-->
            <label for="qualities">
                <h3>As we look for the best mentor match, what is most important to you for qualities in common? (Rank up to three.)</h3>
            </label>
            <br>
            <!--dynamic dropdown from important qualitiess table-->
            <label for="1st quality">Choose 1st quality in common:</label>
            <select name="firstQuality">
                <option value="">--- Select ONE---</option>
                <?php
                $fieldType = 'option';
                $tableName = 'important quality';
                $column = 'important quality';
                $name = 'firstQuality';
                readRefTable($fieldType, $tableName, $column, $name);
                ?>
            </select>
            <br>
            <label for="2nd quality">Choose 2nd quality in common:</label>
            <select name="secondQuality">
                <option value="">--- Select ONE---</option>
                <?php
                $fieldType = 'option';
                $tableName = 'important quality';
                $column = 'important quality';
                $name = 'secondQuality';
                readRefTable($fieldType, $tableName, $column, $name);
                ?>
            </select>
            <br>

            <label for="3rd quality">Choose 3rd quality in common:</label>
            <select name= "thirdQuality">
                <!-- <option value="">--- Select ONE---</option>   -->
                <option value="">--- Select ONE ---</option>
                <?php
                $fieldType = 'option';
                $tableName = 'important quality';
                $column = 'important quality';
                $name = 'thirdQuality';
                readRefTable($fieldType, $tableName, $column, $name);
                ?>
            </select>
            <br>

            <!--If the religious affiliation is selected, use Javascript to create DOM element to show the religion options-->

            <!--Other Comments-->
            <label for="misc_info">
                <h3>Is there anything else that we should consider as we prepare to match you with a mentor?</h3>
            </label>
            <textarea name="misc_info" rows=10 cols=100></textarea>

            <!--Submit the form to the server-->
            <input type="submit" name="submit_survey" value="Submit Survey">

        </form>



    </body>

</html>