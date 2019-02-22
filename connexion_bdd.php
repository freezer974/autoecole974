<?php
    if ($_SERVER['HTTP_HOST'] == 'localhost:8888'):
        /*info pour la base de donnée local */

        $hostname = 'localhost';
        $database = 'bobo';
        $username = 'root';
        $password = 'root';
    else:
        /*info pour la base de donnée heroku */

        $url = getenv('JAWSDB_URL');
        $dbparts = parse_url($url);
        
        $hostname = $dbparts['host'];
        $username = $dbparts['user'];
        $password = $dbparts['pass'];
        $database = ltrim($dbparts['path'],'/');
    endif;

   try {
        $bdd = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }