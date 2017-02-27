<?php
$dbServer = "localhost";

$dbLogin = "****";
$dbPassword = "****";
$dbName = "****";

$dbCharset = "UTF8";

//error_reporting(E_ALL);
//ini_set("display_errors", 1);
function getmicrotime()
{  
      list($usec, $sec) = explode(" ",microtime());  
      return ((float)$usec + (float)$sec);  
}

function sqlConnect($dbServer, $dbLogin, $dbPassword, $dbCharset)	// Connection a la base de donnŽes
{
    mysql_connect($dbServer, $dbLogin, $dbPassword) or exit("[error] unable to connect mysql server [$dbServer] with login [$dbLogin]");
    mysql_query("SET NAMES '$dbCharset'");
}

function sqlStatementExecute($dbName, $statement)	// execute une requete
{
    mysql_select_db($dbName) or exit("[error] unable to select database [$dbName]");
    $result = mysql_query($statement) or exit("[error] unable to execute sql statement [$statement]");
    return $result;
}
?>