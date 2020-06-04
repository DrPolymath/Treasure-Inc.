<?php
    try {	
        $connectionString = "mysql:host=localhost;dbname=TreasureIncDB";
        $databaseUsername = 'TreasureInc';
        $databasePassword = 'wKVWs1fGHWODtGjm';
    
        $pdo = new PDO($connectionString, $databaseUsername, $databasePassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    catch(PDOException $e)
        {
        echo "Database connection failed: " . $e->getMessage();
    }
?>