<?php 
//conectar a sql server :v
//localhost
//$serverName = "CARLOS\SQLEXPRESS"; //serverName\instanceName
//$connectionInfo = array( "Database"=>"usebeqPruebas", "UID"=>"sa", "PWD"=>"lawea");
//$con = sqlsrv_connect( $serverName, $connectionInfo);



//conectar a sql server del server 120 :v
//server remoto
$serverName = "148.220.52.120"; //serverName\instanceName
$connectionInfo = array( "Database"=>"usebeq", "UID"=>"sa", "PWD"=>"usebeq2017CD");
$con = sqlsrv_connect( $serverName, $connectionInfo);


?>