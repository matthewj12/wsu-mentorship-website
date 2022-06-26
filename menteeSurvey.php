<?php

    //We are gonna use PDO 
    include 'includes/autoloader.inc.php';
    // require("includes/config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>MIYA Food Ordering App</title>
    <meta charset="UTF-8">
    <meta name="author" content="Ei Myatnoe Aung">
    <meta name="keywords" content="Mentor, Mentee, WSU, science"> 
    <meta name="description" content="Mentor Program Survey website">
    <meta name="viewport" content="width=device-width, initial-scale="1">

<head>
<body>

    <h1>Hello. Welcome from mentee survey</h1>

    <form method = "post" action = "includes/participant.inc.php">

        <label for="student category">Are you active?:</label>
        <select id="student category" name="student_category">
            <option value="">--- Select ONE ---</option>  
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
        <br>
        <!--Student category-->
        <label for="student category">Are you a mentor or a mentee?:</label>
        <select id="student category" name="student_category">
            <option value="">--- Select ONE ---</option>  
            <option value="1">Mentee</option>
            <option value="0">Mentor</option>
        </select>

        <!--First Name-->
        <label for = "fname"><h3>First Name :</h3></label>
        <input type = "text" name = "fname" required>
        <br>

        <!--Last Name-->
        <label for = "lname"><h3>Last Name :</h3></label>
        <input type = "text" name = "lname" required>
        <br>

        <!--Warrior ID-->
        <label for = "warrior_id"><h3>Warrior_id : </h3></label>
        <input type = "text" name = "warrior_id" required>
        <br>

        <!--Gender ( ONLY ONE CHOICE ALLOWED )-->
        <label for = "gender"><h3>Gender :</h3></label>
        <input type = "radio" name = "gender" value = "male">
        <label for = "gender">Male</label>
        <br>
        <input type = "radio" name = "gender" value = "black">
        <label for = "gender">black</label>
        <br>
        <input type = "radio" name = "gender" value = "non-binary">
        <label for = "gender">Non-binary</label>
        <br>
        <input type = "radio" name = "gender" value = "others">
        <label for = "gender">Other</label>
        <br>
        <input type = "radio" name = "gender" value = "prefer not to answer">
        <label for = "gender">Prefer not to answer</label>
        <br>

        <!--Primary Major ( ONLY ONE CHOICE ALLOWED )-->
        <label for = "primary_major"><h3>What is your primary major in the College of Science and Engineering?</h3></label>
        <input type = "radio" name = "primary_major" value = "biology (allied health or cell molecular)">
        <label for = "primary_major">Biology (Allied Health or Cell Molecular)</label>
        <br>
        <input type = "radio" name="primary_major" value = "biology (ecology or environmental science)">
        <label for = "primary_major">Biology (Ecology or Environmental Science)</label>
        <br>
        <input type="radio" name="primary_major" value = "biology (medical lab science)">
        <label for="primary_major">Biology—Medical Lab Science </label>
        <br>
        <input type="radio" name="primary_major" value = "biology (radiography)">
        <label for="primary_major">Biology—Radiography</label>
        <br>
        <input type="radio" name="primary_major" value = "chemistry">
        <label for="primary_major">Chemistry</label>
        <br>
        <input type="radio" name="primary_major" value = "composite materials engineering">
        <label for="primary_major">Composite Materials Engineering</label>
        <br>
        <input type="radio" name="primary_major" value = "computer science">
        <label for="primary_major">Computer Science</label>
        <br>
        <input type="radio" name="primary_major" value = "data science">
        <label for="primary_major">Data Science</label>
        <br>
        <input type="radio" name="primary_major" value = "general engineering">
        <label for="primary_major">General Engineering</label>
        <br>
        <input type="radio" name="primary_major" value = "geoscience">
        <label for="primary_major">Geoscience</label>
        <br>
        <input type="radio" name="primary_major" value = "math">
        <label for="primary_major">Math</label>
        <br>
        <input type="radio" name="primary_major" value = "physics">
        <label for="primary_major">Physics</label>
        <br>
        <input type="radio" name="primary_major" value = "statistics">
        <label for="primary_major">Statistics</label>
        <br>
        <input type="radio" name="primary_major" value = "undecided">
        <label for="primary_major">I’m undecided…thinking about a major</label>
        <br>

        <!--professional programs-->
        <label for = "pre_programs"><h3>Are you pursuing any of the following professional programs?(Leave blank if none of these apply.)</h3></label>
        <input type = "radio" name = "pre_programs" value = "dentistry">
        <label for = "pre_programs">Pre-dentistry</label>
        <br>
        <input type = "radio" name = "pre_programs" value = "forensics">
        <label for = "pre_programs">Pre-forensics</label>
        <br>
        <input type = "radio" name = "pre_programs" value = "medicine">
        <label for = "pre_programs">Pre-medicine</label>
        <br>
        <input type = "radio" name = "pre_programs" value = "occupational therapy">
        <label for = "pre_programs">Pre-occupational therapy</label>
        <br>
        <input type = "radio" name = "pre_programs" value = "optometry">
        <label for = "pre_programs">Pre-optometry</label>
        <br>
        <input type = "radio" name = "pre_programs" value = "pharmacy">
        <label for = "pre_programs">Pre-pharmacy </label>
        <br>
        <input type = "radio" name = "pre_programs" value = "physical therapy">
        <label for = "pre_programs">Pre-physical therapy</label>
        <br>
        <input type = "radio" name = "pre_programs" value = "physician assistant" >
        <label for = "pre_programs">Pre-physician assistant</label>
        <br>
        <input type = "radio" name = "pre_programs" value = "other" >
        <label for = "pre_programs">Other</label>
        <input type = "text" name = "pre_programs">
        <br>

        <!--religion-->
        <label for = "religion"><h3>Are you following any religion?</h3></label>
        <input type = "radio" name = "religion" value = "christianity">
        <label for = "religion">christianity</label>
        <br>
        <input type = "radio" name = "religion" value = "judaism">
        <label for = "religion">judaism</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "islam">
        <label for = "religion">islam</label>
        <br>
        <input type = "radio" name = "religion" value = "buddhism">
        <label for = "religion">buddhism</label>
        <br>
        <input type = "radio" name = "religion" value = "hinduism">
        <label for = "gender">hinduism</label>
        <br>
        <input type = "radio" name = "religion" value = "taoism">
        <label for = "relgiion">taoism</label>
        <br>
        <input type = "radio" name = "religion" value = "spiritual but not religious">
        <label for = "religion">spiritual but not religious</label>
        <br>
        <input type = "radio" name = "religion" value = "agnostic">
        <label for = "religion">agnostic</label>
        <br>
        <input type = "radio" name = "religion" value = "atheist">
        <label for = "religion">atheist</label>
        <br>
        <input type = "radio" name = "religion" value = "pastafarian">
        <label for = "religion">pastafarian</label>
        <br>
        <input type = "radio" name = "religion" value = "other">
        <label for = "religion">other</label>
        <br>
        <input type = "radio" name = "religion" value = "prefers not to answer">
        <label for = "religion">prefers not to answer</label>
        <br>
        <input type = "radio" name = "religion" value = "doesn't matter">
        <label for = "religion">Doesn't matter</label>
        <br>
        
        <!--Student Category individual version-->
        <label for = "category[]"><h3>Please check all that you are comfortable sharing.</h3></label>
        <input type = "checkbox" name = "international_student" value = "1">
        <label for = "international_student">International student</label>
        <br>
        <input type = "checkbox" name = "student_athlete" value = "1">
        <label for = "student_athlete">Student athlete</label>
        <br>
        <input type = "checkbox" name = "multilingual" value = "1">
        <label for = "multilingual">Speak two or more languages</label>
        <br>
        <input type = "checkbox" name = "not_born_in_US" value = "1">
        <label for = "not_born_in_US">Not born in this country</label>
        <br>
        <input type = "checkbox" name = "firstgen_student" value = "1">
        <label for = "firstgen_student">First in my family to attend college</label>
        <br>
        <input type = "checkbox" name = "lgbtq+" value = "1">
        <label for = "lgbtq+">LGBTQ+</label>
        <br>
        <input type = "checkbox" name = "transfer_student" value = "1">
        <label for = "transfer_student">Transferred from another college</label>
        <br>
        <input type = "checkbox" name = "undecided" value = "1">
        <label for = "undecided">Unsure or undecided about my major</label>
        <br>

        <!--Associated Departments-->
        <label for = "interest_diversity"><h3>Are you or do you plan to associate with any of the following departments?</h3></label>
        International Office <br>
        Office of Equity and Inclusive Excellence<br>
        TRIO<br>
        <br>
        <input type = "radio" name = "interest_diversity" value = "1">
        <label for = "interest_diversity">Yes</label>
        <input type = "radio" name = "interest_diversity" value = "0">
        <label for = "interest_diversity">No</label>
        <br>

        <!--Dynamic radio list-->
        <label for = "languages"><h3>Please identify any languages beyond English that you speak.</h3></label>
        <!--dynamic dropdown from second language table-->
            <?php
                $stmt = "SELECT * from `second language`";
                $result = mysqli_query($conn, $stmt);
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        ?>
                        <input type = "checkbox" name = "languages[]" value="<?php echo $row["second language"] ?>">
                        <label for = "languages[]"><?php echo $row["second language"] ?></label>
                        <br>
                        <?php
                        
                    }
                }
            ?>


        <!--Ethnicity/gender-->
        <label for = "ethnicity[]"><h3>What is your ethnicity/gender?  (Select multiple ethnicities if applicable.)</h3></label>
        <!--dynamic dropdown from race table-->
        <?php
                $stmt = "SELECT * from `race`";
                $result = mysqli_query($conn, $stmt);
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        ?>
                        <input type = "checkbox" name = "ethnicity[]" value="<?php echo $row["race"] ?>">
                        <label for = "ethnicity[]"><?php echo $row["race"] ?></label>
                        <br>
                        <?php
                        
                    }
                }
        ?>



        <!--Dynamic radio list-->
        <label for = "hobbies[]"><h3>Hobbies/Interests:  Check all items that are important to you:</h3></label>
        <!--dynamic dropdown from important qualities table-->
            <?php
                $stmt = "SELECT * from `hobby`";
                $result = mysqli_query($conn, $stmt);
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        ?>
                        <input type = "checkbox" name = "hobbies[]" value="<?php echo $row["hobby"] ?>">
                        <label for = "hobbies[]"><?php echo $row["hobby"] ?></label>
                        <br>
                        <?php
                        
                    }
                }
            ?>


        <!--Mentor Gender-->
        <label for = "mentor_gender"><h3>I prefer to work with a mentor who is:</h3></label>
        <input type = "radio" name = "mentor_gender" value = "male">
        <label for = "mentor_gender">Male</label>
        <br>
        <input type = "radio" name = "mentor_gender" value = "black">
        <label for = "mentor_gender">black</label>
        <br>
        <input type = "radio" name = "mentor_gender" value = "non-binary">
        <label for = "mentor_gender">Non-binary</label>
        <br>
        <input type = "radio" name = "mentor_gender" value = "other">
        <label for = "mentor_gender">Other</label>
        <br>
        <input type = "radio" name = "mentor_gender" value = "prefers not to answer">
        <label for = "mentor_gender">Prefers not to answer</label>
        <br>
        <input type = "radio" name = "mentor_gender" value = "doesn't matter">
        <label for = "mentor_gender">Doesn't matter</label>
        <br>

        <!--Mentor Race-->

        <label for = "mentor_race"><h3>I prefer to work with a mentor who is:</h3></label>
        <input type = "radio" name = "mentor_race" value = "asian">
        <label for = "mentor_race">Asian</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "african">
        <label for = "mentor_race">African</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "american indian">
        <label for = "mentor_race">American Indian</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "black">
        <label for = "mentor_race">Black</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "hispanic">
        <label for = "mentor_race">Hispanic</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "native hawaiian">
        <label for = "mentor_race">Native Hawaiian</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "white">
        <label for = "mentor_race">White</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "mixed">
        <label for = "mentor_race">Mixed</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "other">
        <label for = "mentor_race">Other</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "prefers not to answer">
        <label for = "mentor_race">Prefers not to answer</label>
        <br>
        <input type = "radio" name = "mentor_race" value = "doesn't matter">
        <label for = "mentor_race">Doesn't matter</label>
        <br>

        <!--Mentor Religion-->
        <label for = "mentor_religion"><h3>I prefer to work with a mentor who is:</h3></label>
        <input type = "radio" name = "mentor_religion" value = "christianity">
        <label for = "mentor_religion">christianity</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "judaism">
        <label for = "mentor_religion">judaism</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "islam">
        <label for = "mentor_religion">islam</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "buddhism">
        <label for = "mentor_religion">buddhism</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "hinduism">
        <label for = "mentor_gender">hinduism</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "taoism">
        <label for = "mentor_relgiion">taoism</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "spiritual but not religious">
        <label for = "mentor_religion">spiritual but not religious</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "agnostic">
        <label for = "mentor_religion">agnostic</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "atheist">
        <label for = "mentor_religion">atheist</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "pastafarian">
        <label for = "mentor_religion">pastafarian</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "other">
        <label for = "mentor_religion">other</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "prefers not to answer">
        <label for = "mentor_religion">prefers not to answer</label>
        <br>
        <input type = "radio" name = "mentor_religion" value = "doesn't matter">
        <label for = "mentor_religion">Doesn't matter</label>
        <br>


        <!--Top three qualities-->
        <label for = "qualities"><h3>As we look for the best mentor match, what is most important to you for qualities in common?  (Rank up to three.)</h3></label>
        <br>
        <!--dynamic dropdown from important qualitiess table-->
        <label for = "1st quality">Choose 1st quality in common:</label>
        <select name = "1st quality">
            <option value="">--- Select ONE---</option>  
            <?php
                $stmt = "SELECT * from `important quality`";
                $result = mysqli_query($conn, $stmt);
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        ?>
                        <option name = "<?php echo $row["important quality"] ?>" value="major"><?php echo $row["important quality"] ?></option>  
                        <?php
                    }
                }

                //if religion affiliatio is chosen
                    //use javascript to add DOM element and read the religions
            ?>
        </select>
        <br>
        <label for = "2nd quality">Choose 2nd quality in common:</label>
        <select name = "2nd quality">
            <option value="">--- Select ONE---</option>  
            <?php
                $stmt = "SELECT * from `important quality`";
                $result = mysqli_query($conn, $stmt);
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        ?>
                        <option name = "<?php echo $row["important quality"] ?>" value="major"><?php echo $row["important quality"] ?></option>  
                        <?php
                    }
                }
            ?>
        </select>
        <br>
        <label for = "3rd quality">Choose 3rd quality in common:</label>
        <select name = "3rd quality">
            <option value="">--- Select ONE---</option>  
            <?php
                $stmt = "SELECT * from `important quality`";
                $result = mysqli_query($conn, $stmt);
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        ?>
                        <option name = "<?php echo $row["important quality"] ?>" value="major"><?php echo $row["important quality"] ?></option>  
                        <?php
                    }
                }

                    //if religion affiliation is selected
                    //use javascript to add DOM element and read the religions
            ?>
        </select>
        <br>


        <!--If the religious affiliation is selected, use Javascript to create DOM element to show the religion options-->

        <!--Other Comments-->
        <label for = "misc_info"><h3>Is there anything else that we should consider as we prepare to match you with a mentor?</h3></label>
        <textarea name="misc_info" rows= 10 cols=100></textarea>

        <!--Submit the form to the server-->
        <input type="submit" name = "submit_survey" value="Submit Survey">

    </form>



</body>
</html>
