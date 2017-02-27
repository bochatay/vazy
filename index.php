<?php
/*
    VAZY Online vase design tool
    Copyright (C) 2017  Valentin Bochatay
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program. If not, see <http://www.gnu.org/licenses/>.

*/
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
