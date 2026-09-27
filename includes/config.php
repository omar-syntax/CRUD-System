<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "workshop_5";
$conn = mysqli_connect($host,$username,$password,$dbname);

if (!$conn){
    die("Can't vonnect to the database.");
};

?>