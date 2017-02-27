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
