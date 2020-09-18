<?php

    //Get HKMA Capital Markets data
    $capitalMarketsDataFeed = json_decode(file_get_contents("../raw_data/hkma_data/capitalmarkets_stats.json"), true);

    //Get HKMA Monetary data
    $monetaryDataFeed = json_decode(file_get_contents("../raw_data/hkma_data/monetary_stats.json"), true);

    //Get HKMA Economics data
    $econDataFeed = json_decode(file_get_contents("../raw_data/hkma_data/econ_stats.json"), true);

    //Create database connection
    $host = "localhost";
    $database = "hkhousinganalysis";
    $username = "root";
    $password = "";


    $connection = mysqli_connect($host, $username, $password, $database) or die("Could not connect to database");

/* Capital Markets Data */

    //Creates HKMA_CapitalMarkets_Data table if not exists

    $capitalmarkets_data_table_name = "hkma_capitalmarkets_data";
    
    $checkCapitalMarketsTableExistsQuery = mysqli_query($connection, "SHOW TABLES WHERE `Tables_in_hkhousinganalysis` = '$capitalmarkets_data_table_name';");

    if(mysqli_num_rows($checkCapitalMarketsTableExistsQuery) == 0)
    {
        $capitalMarketsTableCreationQuery = "CREATE TABLE `$capitalmarkets_data_table_name`(";

        $primaryKeyID = key($capitalMarketsDataFeed['result']['records'][0]);

        //echo $primaryKeyID;

        foreach(array_keys($capitalMarketsDataFeed['result']['records'][0]) as $column)
        {
            $capitalMarketsTableCreationQuery .= "`" . $column . "` VARCHAR(255) NOT NULL,";
        }    

        $capitalMarketsTableCreationQuery = substr_replace($capitalMarketsTableCreationQuery, "", -1);
        //echo $capitalMarketsTableCreationQuery;
        
        $createCapitalMarketsTable = mysqli_query($connection, $capitalMarketsTableCreationQuery . ", PRIMARY KEY (`$primaryKeyID`));");

        if($createCapitalMarketsTable)
        {
            echo "Creation of " . $capitalmarkets_data_table_name . " succcessful" . "\n";
        }
        else
        {
            echo "Failed to create " . $capitalmarkets_data_table_name . "\n";
        }
    }

    //Check if any non-existing columns, if yes, add to table
    $checkColumnsMatch = mysqli_query($connection, "SELECT * FROM `hkma_capitalmarkets_data` LIMIT 1;");

    $checkColumnsMatchResultKeys = array_keys(mysqli_fetch_assoc($checkColumnsMatch));

    if(!($checkColumnsMatchResultKeys === array_keys($capitalMarketsDataFeed['result']['records'][0])))
    {
        $addNewColumnsQuery = "ALTER TABLE `hkma_capitalmarkets_data` ADD ";

        foreach(array_keys($capitalMarketsDataFeed['result']['records'][0]) as $testColumn)
        {
            if(!(in_array($testColumn, $checkColumnsMatchResultKeys)))
            {
                $addNewColumnsQuery .= "`" . $testColumn . "` VARCHAR(255) NOT NULL,";
            }
        }

        $addNewColumnsQuery = substr_replace($addNewColumnsQuery, "", -1);

        $addNewColumns = mysqli_query($connection, $addNewColumnsQuery . ";");
        if($addNewColumns)
        {
            echo "New columns added to " . $capitalmarkets_data_table_name . "\n";
        }
        else
        {
            echo "Failed to add new columns to " . $capitalmarkets_data_table_name . "\n";
            echo $addNewColumnsQuery;
        }
        
    }


        //Insert bulk into Capital Markets table

        $importCapitalMarketsDataQuery = "INSERT IGNORE INTO `$capitalmarkets_data_table_name`(";

        foreach(array_keys($capitalMarketsDataFeed['result']['records'][0]) as $column)
        {
            $importCapitalMarketsDataQuery .= "`" . $column . "`,";
        } 
        $importCapitalMarketsDataQuery = substr_replace($importCapitalMarketsDataQuery, "", -1);

        $importCapitalMarketsDataQuery .= ") VALUES ";

        $counter = 0;
        foreach($capitalMarketsDataFeed['result']['records'] as $row)
        {
            $importCapitalMarketsDataQuery .= "(";
            foreach($row as $rowEntry)
            {
                $importCapitalMarketsDataQuery .= "'$rowEntry',";
            }
            $importCapitalMarketsDataQuery = substr_replace($importCapitalMarketsDataQuery, "", -1);
            $importCapitalMarketsDataQuery .= "),";
            $counter++;
        }

        $importCapitalMarketsDataQuery = substr_replace($importCapitalMarketsDataQuery, "", -1, 2);
        //echo $importCapitalMarketsDataQuery;
        //echo $counter;
        
        
        $importCapitalMarketsData = mysqli_query($connection, $importCapitalMarketsDataQuery . ";");

        if($importCapitalMarketsData)
        {
            echo "Successfully inserted/updated " . $capitalmarkets_data_table_name . "\n";
        }
        else
        {
            echo "Failed to insert/update " . $capitalmarkets_data_table_name . "\n";
            echo $importCapitalMarketsDataQuery . "/n/n/n";
        }

/* Monetary Data */

    //Creates HKMA_Monetary_Data table if not exists

    $monetary_data_table_name = "hkma_monetary_data";
    
    $checkMonetaryTableExistsQuery = mysqli_query($connection, "SHOW TABLES WHERE `Tables_in_hkhousinganalysis` = '$monetary_data_table_name';");

    if(mysqli_num_rows($checkMonetaryTableExistsQuery) == 0)
    {
        $monetaryTableCreationQuery = "CREATE TABLE `$monetary_data_table_name`(";

        $primaryKeyID = key($monetaryDataFeed['result']['records'][0]);

        //echo $primaryKeyID;

        foreach(array_keys($monetaryDataFeed['result']['records'][0]) as $column)
        {
            $monetaryTableCreationQuery .= "`" . $column . "` VARCHAR(255) NOT NULL,";
        }    

        $monetaryTableCreationQuery = substr_replace($monetaryTableCreationQuery, "", -1);
        //echo $capitalMarketsTableCreationQuery;
        
        $createMonetaryTable = mysqli_query($connection, $monetaryTableCreationQuery . ", PRIMARY KEY (`$primaryKeyID`));");

        if($createMonetaryTable)
        {
            echo "Creation of " . $monetary_data_table_name . " succcessful" . "\n";
        }
        else
        {
            echo "Failed to create " . $monetary_data_table_name . "\n";
        }
    }

    //Check if any non-existing columns, if yes, add to table
    $checkColumnsMatch = mysqli_query($connection, "SELECT * FROM `hkma_monetary_data` LIMIT 1;");

    $checkColumnsMatchResultKeys = array_keys(mysqli_fetch_assoc($checkColumnsMatch));

    if(!($checkColumnsMatchResultKeys === array_keys($monetaryDataFeed['result']['records'][0])))
    {
        $addNewColumnsQuery = "ALTER TABLE `hkma_monetary_data` ADD ";

        foreach(array_keys($monetarysDataFeed['result']['records'][0]) as $testColumn)
        {
            if(!(in_array($testColumn, $checkColumnsMatchResultKeys)))
            {
                $addNewColumnsQuery .= "`" . $testColumn . "` VARCHAR(255) NOT NULL,";
            }
        }

        $addNewColumnsQuery = substr_replace($addNewColumnsQuery, "", -1);

        $addNewColumns = mysqli_query($connection, $addNewColumnsQuery . ";");
        if($addNewColumns)
        {
            echo "New columns added to " . $monetary_data_table_name . "\n";
        }
        else
        {
            echo "Failed to add new columns to " . $monetary_data_table_name . "\n";
            echo $addNewColumnsQuery;
        }
        
    }

        //Insert bulk into Capital Markets table

        $importMonetaryDataQuery = "INSERT IGNORE INTO `$monetary_data_table_name`(";

        foreach(array_keys($monetaryDataFeed['result']['records'][0]) as $column)
        {
            $importMonetaryDataQuery .= "`" . $column . "`,";
        } 
        $importMonetaryDataQuery = substr_replace($importMonetaryDataQuery, "", -1);

        $importMonetaryDataQuery .= ") VALUES ";

        $counter = 0;
        foreach($monetaryDataFeed['result']['records'] as $row)
        {
            $importMonetaryDataQuery .= "(";
            foreach($row as $rowEntry)
            {
                $importMonetaryDataQuery .= "'$rowEntry',";
            }
            $importMonetaryDataQuery = substr_replace($importMonetaryDataQuery, "", -1);
            $importMonetaryDataQuery .= "),";
            $counter++;
        }

        $importMonetaryDataQuery = substr_replace($importMonetaryDataQuery, "", -1, 2);
        //echo $importCapitalMarketsDataQuery;
        //echo $counter;
        
        
        $importMonetaryData = mysqli_query($connection, $importMonetaryDataQuery . ";");

        if($importMonetaryData)
        {
            echo "Successfully inserted/updated " . $monetary_data_table_name . "\n";
        }
        else
        {
            echo "Failed to insert/update " . $monetary_data_table_name . "\n";
        }

/* Econ Data */

    //Creates HKMA_Monetary_Data table if not exists

    $econ_data_table_name = "hkma_econ_data";
    
    $checkEconTableExistsQuery = mysqli_query($connection, "SHOW TABLES WHERE `Tables_in_hkhousinganalysis` = '$econ_data_table_name';");

    if(mysqli_num_rows($checkEconTableExistsQuery) == 0)
    {
        $EconTableCreationQuery = "CREATE TABLE `$econ_data_table_name`(";

        $primaryKeyID = key($econDataFeed['result']['records'][0]);

        //echo $primaryKeyID;

        foreach(array_keys($econDataFeed['result']['records'][0]) as $column)
        {
            $EconTableCreationQuery .= "`" . $column . "` VARCHAR(255) NOT NULL,";
        }    

        $EconTableCreationQuery = substr_replace($EconTableCreationQuery, "", -1);
        //echo $capitalMarketsTableCreationQuery;
        
        $createEconTable = mysqli_query($connection, $EconTableCreationQuery . ", PRIMARY KEY (`$primaryKeyID`));");

        if($createEconTable)
        {
            echo "Creation of " . $econ_data_table_name . " succcessful" . "\n";
        }
        else
        {
            echo "Failed to create " . $econ_data_table_name . "\n";
        }
    }

    //Check if any non-existing columns, if yes, add to table
    $checkColumnsMatch = mysqli_query($connection, "SELECT * FROM `hkma_econ_data` LIMIT 1;");

    $checkColumnsMatchResultKeys = array_keys(mysqli_fetch_assoc($checkColumnsMatch));

    if(!($checkColumnsMatchResultKeys === array_keys($econDataFeed['result']['records'][0])))
    {
        $addNewColumnsQuery = "ALTER TABLE `hkma_econ_data` ADD ";

        foreach(array_keys($econDataFeed['result']['records'][0]) as $testColumn)
        {
            if(!(in_array($testColumn, $checkColumnsMatchResultKeys)))
            {
                $addNewColumnsQuery .= "`" . $testColumn . "` VARCHAR(255) NOT NULL,";
            }
        }

        $addNewColumnsQuery = substr_replace($addNewColumnsQuery, "", -1);

        $addNewColumns = mysqli_query($connection, $addNewColumnsQuery . ";");
        if($addNewColumns)
        {
            echo "New columns added to " . $econ_data_table_name . "\n";
        }
        else
        {
            echo "Failed to add new columns to " . $econ_data_table_name . "\n";
            echo $addNewColumnsQuery;
        }
        
    }

        //Insert bulk into Capital Markets table

        $importEconDataQuery = "INSERT IGNORE INTO `$econ_data_table_name`(";

        foreach(array_keys($econDataFeed['result']['records'][0]) as $column)
        {
            $importEconDataQuery .= "`" . $column . "`,";
        } 
        $importEconDataQuery = substr_replace($importEconDataQuery, "", -1);

        $importEconDataQuery .= ") VALUES ";

        $counter = 0;
        foreach($econDataFeed['result']['records'] as $row)
        {
            $importEconDataQuery .= "(";
            foreach($row as $rowEntry)
            {
                $importEconDataQuery .= "'$rowEntry',";
            }
            $importEconDataQuery = substr_replace($importEconDataQuery, "", -1);
            $importEconDataQuery .= "),";
            $counter++;
        }

        $importEconDataQuery = substr_replace($importEconDataQuery, "", -1, 2);
        //echo $importCapitalMarketsDataQuery;
        //echo $counter;
        
        
        $importEconData = mysqli_query($connection, $importEconDataQuery . ";");

        if($importEconData)
        {
            echo "Successfully inserted/updated " . $econ_data_table_name . "\n";
        }
        else
        {
            echo "Failed to insert/update " . $econ_data_table_name . "\n";
        }        
?>