<?php

    function connect()
    {
        $serverName = "localhost";
        $dbUsername = "root";
        $dbPassword = "Sql783knui1-1l;/klaa-9";
        $dbName = "mp";

        try
        {
            $dsn = 'mysql:host='.$serverName.';dbname='.$dbName;
            $pdo = new PDO($dsn, $dbUsername, $dbPassword);
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
    //function to get single choice input from html form
    //return array
    function assignSingle($inputField)
    {
        if(isset($_POST['$inputField']))
        {
            echo "This participant is a ". $_POST['$inputField'];
            $inputField = $_POST[$inputField];
        }

        echo $inputField;
    }

    //function to get multiple choice information from html form 
    //and return array
    function assignStrArray($inputField)
    {
        $inputList=$_POST['$inputField'];  
        $chk="";  
        foreach($inputList as $chk1)  
            {  
                $chk .= $chk1.",";  
            }  
        
        $assoc_dept = $chk;
        echo "Chosen associations are : ". $chk;
        return $inputList;
    }

    //function to get boolean array from html form
    //e.g. comfortable sharing
    //maybe like use case switchas
    function assignBooleanArray($inputField)
    {
        if(isset($_POST['$inputField']))
        {
        }
        return $inputField;
    }

    //Mysqli way
    // function readEnumValues($open, $closeTag, $tableName, $dbName, $columnName, $conn)
    // {
    //     $sql = "SELECT
    //         SUBSTRING(COLUMN_TYPE, 6, LENGTH(COLUMN_TYPE) - 6) AS val
    //     FROM
    //         information_schema.COLUMNS      
    //     WHERE
    //         -- TABLE_SCHEMA='mp' 
    //         TABLE_SCHEMA = ?

    //     AND
    //         TABLE_NAME = ?
    //     AND
    //         COLUMN_NAME = ?";
    //     $result = mysqli_query($conn, $sql);
    //     if(mysqli_num_rows($result) > 0)
    //     {
    //         while ($row = mysqli_fetch_row($result)) 
    //         {
    //             $options = str_getcsv($row[0], ',', "'");
    //             echo print_r($options);
    //             foreach($options as $option)
    //             {
    //                 echo "$open".$option."$closeTag";
    //             }
    //         }
    //     }

    //     else
    //     {
    //         echo "SQL Query Failed";
    //     }


    //         //     $sql = "SELECT
    //         //     SUBSTRING(COLUMN_TYPE, 6, LENGTH(COLUMN_TYPE) - 6) AS val
    //         // FROM
    //         //     information_schema.COLUMNS      
    //         // WHERE
    //         //     TABLE_SCHEMA= '$dbName'
    //         // AND
    //         //     TABLE_NAME = '$tableName'
    //         // AND
    //         //     COLUMN_NAME = '$columnName'";
    //         //     $result = mysqli_query($conn, $sql);
    //         //     echo "<$fieldType>".mysqli_num_rows($result)."</$fieldType>";
    //         //     echo var_dump($result);
    //         //     if(mysqli_num_rows($result) > 0)
    //         //     {
    //         //         echo "Results Found";
    //         //         // echo var_dump($result);
    //         //         // while ($row = mysqli_fetch_row($result)) 
    //         //         // {
    //         //         //     $options = str_getcsv($row[0], ',', "'");
    //         //         //     echo print_r($options);
    //         //         //     foreach($options as $option)
    //         //         //     {
    //         //         //         echo "<$fieldType>".$option."</$fieldType>";
    //         //         //     }
    //         //         // }
    //         //     }

    //         //     else
    //         //     {
    //         //         echo "No results found";
    //         //     }

    // }

    function readEnumValues($fu, $dbName, $tableName, $columnName)
    {
        
        //PDO way
        try
        {
            $sql = "SELECT
            SUBSTRING(COLUMN_TYPE, 6, LENGTH(COLUMN_TYPE) - 6) AS val
        FROM
            information_schema.COLUMNS      
        WHERE
            TABLE_SCHEMA= ?
        AND
            TABLE_NAME = ?
        AND
            COLUMN_NAME = ?";
            $stmt = connect()->prepare($sql);
            // $stmt = connect()->prepare($sql);
            
            if($stmt->execute([$dbName, $tableName, $columnName]))
            {
                $stmt->execute([$dbName, $tableName, $columnName]);
                $values = $stmt->fetchAll();
                // echo $values[0]["val"];
                $options = str_getcsv($values[0]["val"], ',', "'");
                // echo var_dump($options);

                switch ($fu) 
                {
                    case 'option':
                        ?>
                        <br>
                        <?php
                        foreach($options as $option)
                        {
                            // echo "<option>"."Hello"."</option>";
                            echo "<option>".$option."</option>";
                        }

                      break;
                    case 'checkbox':
                        foreach($options as $option)
                        {
                        ?>
                        <br>
                        <input type = "checkbox" name = "<?php echo $option.'[]'?>" value="<?php echo $option ?>">
                        <label for = "<?php echo $option.'[]'?>" ><?php echo $option ?></label>
                        
                        <?php
                        }

                      break;
                    case 'radio':
                        foreach($options as $option)
                        {
                            ?>
                            <br>
                            <input type = "radio" name = "<?php echo $option?>" value="<?php echo $option?>">
                            <label for = "<?php echo $option?>"><?php echo $option ?></label>
                            <?php
                        }
                      break;
                        
                    default:
                        echo "Invalid Input field";
                }

            }

            else
            {
                echo "Query Failed";
            }

        }
        catch(PDOException $e)
        {
            echo $e->getMessage();        
        }
    
    }

    function readRefTable($fieldType,$tableName, $columnName, $conn)
    {
        //Mysqli way
        $sql = "SELECT * from `$tableName`";
        $stmt = connect()->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
        $stmt = "SELECT * from `$tableName`";
        $result = mysqli_query($conn, $stmt);
        if(mysqli_num_rows($result) > 0)
        {
            switch ($fieldType) 
            {
                case 'option':
                    ?>
                    <br>
                    <?php
                while($row = mysqli_fetch_assoc($result))
                {
                    ?>
                    <option name = "<?php echo $row[$columnName] ?>" value="<?php echo $row[$columnName] ?>"><?php echo $row[$columnName] ?></option>  
                    <?php
                    
                }

                break;
                case 'checkbox':
                    while($row = mysqli_fetch_assoc($result))
                    {
                        ?>
                        <br>
                        <input type = "checkbox" name = "<?php echo $row[$columnName].'[]'?>" value= "<?php echo $row[$columnName] ?>">
                        <label for = "<?php echo $row[$columnName].'[]'?>"><?php echo $row[$columnName] ?></label>
                        <?php
                        
                    }

                  break;
                case 'radio':
                    while($row = mysqli_fetch_assoc($result))
                    {
                        ?>
                            <br>
                            <input type = "radio" name = "<?php echo $row[$columnName]?>" value="<?php echo $row[$columnName]?>">
                            <label for = "<?php echo $option?>"><?php echo $row[$columnName] ?></label>
                        <?php
                        
                    }
                  break;
                    
                default:
                    echo "Invalid Input field";
            }

        }

    }

    function readBooleanValues()
    {

    }