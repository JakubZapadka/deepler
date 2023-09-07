<?php
require './env-read.php';
function db_connect(){
    //LOCALHOST
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db_name = "deepler";
    //SERVER
    /*
    $servername = getenv(DB_HOST);
    $username = getenv(DB_USER);
    $password = getenv(DB_PASS);
    $db_name = getenv(DB_NAME);
    */
    try
    {
        // Create connection
        global $db;
        $db = mysqli_connect($servername, $username, $password, $db_name);
    }
    catch (Exception $e)
    {
        die('Wystąpił wyjątek nr '.$e->getCode().', '.$e->getMessage());
    }
}
db_connect();
?>