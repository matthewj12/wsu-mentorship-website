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
        if(isset($_POST[$inputField]))
        {
            echo "This participant is a ". $_POST[$inputField];
            $inputField = $_POST[$inputField];
        }
        echo $inputField;
    }

    //function to get multiple choice information from html form 
    //and return array
    function converArrtoStr($inputArr)
    {
        $str = implode(', ', $inputArr);
        return $str;
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

 
    function readEnumValues($fu, $dbName, $tableName, $columnName, $name)
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
                        ?>
                            <option name = "<?php echo $name?>" value = "<?php echo $option ?>"><?php echo $option ?></option>
                            
                        <?php
                        }

                      break;
                    case 'checkbox':
                        foreach($options as $option)
                        {
                            $nameArr = $name.'[]';
                        ?>
                        <br>
                        <input type = "checkbox" name = "<?php echo $nameArr?>" value="<?php echo $option ?>">
                        <label for = "<?php echo $nameArr ?>" ><?php echo $option ?></label>
                        
                        <?php
                        }

                      break;
                    case 'radio':
                        foreach($options as $option)
                        {
                            ?>
                            <br>
                            <input type = "radio" name = "<?php echo $name ?>" value="<?php echo $option?>">
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

    function readRefTable($fieldType, $tableName, $columnName, $name)
    {
    try 
    {
        $sql = "SELECT * from `$tableName`";
        $stmt = connect()->prepare($sql);
        if ($stmt->execute()) {
            $stmt->execute();
            $result = $stmt->fetchAll();
            // print_r($result);

                // echo "<br>";
                // echo $row[$columnName];
                switch ($fieldType) {
                    case 'option':
                    ?>
                        <br>
                        <?php
                        foreach($result as $row)
                        {
                        ?>
                            <option name="<?php echo $name ?>" value="<?php echo $row[$columnName] ?>"><?php echo $row[$columnName] ?></option>
                        <?php

                        }

                        break;
                    case 'checkbox':
                        foreach($result as $row){
                            $nameArr = $name.'[]';
                        ?>
                            <br>
                            <input type="checkbox" name="<?php echo $nameArr ?>" value="<?php echo $row[$columnName] ?>">
                            <label for="<?php echo $nameArr ?>"><?php echo $row[$columnName] ?></label>
                        <?php

                        }

                        break;
                    case 'radio':
                        foreach($result as $row){
                        ?>
                        
                            <br>
                            <input type="radio" name="<?php echo $name ?>" value="<?php echo $row[$columnName] ?>">
                            <label for="<?php echo $name ?>"><?php echo $row[$columnName] ?></label>
                        <?php

                        }
                        break;

                    default:
                        echo "Invalid Input field";
                }
            
            }
    } 
    catch (PDOException $e) 
    {
        echo $e;
    }
}

    function readBooleanValues()
    {

    }