<?php

require_once("./conf.php");
$time_start = getmicrotime(); 
session_cache_limiter('public, must-revalidate');

session_start();



// **************************************************************
// ********* Connexion a la base de donnees pour futures requetes
// **************************************************************
sqlConnect($dbServer, $dbLogin, $dbPassword, $dbCharset); 

// *******************************************
// ********* VARIABLES
// *******************************************
$htmlOutput = 				file_get_contents("./main.php");

//$htmlOutput = str_replace("%test%"	, $s	, $htmlOutput );	// Affichage du temps de la requete
//$htmlOutput = str_replace("%data%"	, ""	, $htmlOutput );	// Affichage du temps de la requete

//$time_end = getmicrotime();
//$time = $time_end - $time_start;
//$time = "Page générée en ".substr($time,0,7)." secondes";
//$htmlOutput = str_replace("%tempsrequete%"	, $time	, $htmlOutput );	// Affichage du temps de la requete


// **************************************************
// ********* AFFICHAGE
// **************************************************
echo $htmlOutput;

//mysql_close();
?>