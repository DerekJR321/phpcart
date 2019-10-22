<?php

// database config (temp.. will be using json)
$dbHost             =   'localhost';
$dbUsername         =   'root';
$dbPassword         =   '';
$dbName             =   'phpcart';

// create db connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// check connection
if($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}

