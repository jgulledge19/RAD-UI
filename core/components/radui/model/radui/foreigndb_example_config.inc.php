<?php

$database_type = 'mysql';
$database_server = 'localhost';
$database_connection_charset = 'utf8';
 
$dbase = 'databaseName';
/**
 * need the following 3 defined
 */
$db_dsn = $database_type.':host='.$database_server.';dbname='.$dbase.';charset='.$database_connection_charset;
$db_user = 'your_user';
$db_password = 'your_password';
