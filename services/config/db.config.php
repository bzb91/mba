<?php
/**
 * Created by PhpStorm.
 * User: Burak
 * Date: 2.5.2018
 * Time: 08:13
 */

$servername = "localhost";
$username = "burak";
$password = "";
$db = "egitim";

// Create connection
$conn = new mysqli($servername, $username, $password,$db) or die("Connect failed: %s\n". $conn -> error);
//$conn = mysqli_connect($servername, $username, $password,$db);

mysqli_query($conn,"SET NAMES 'utf8'  ");
mysqli_query($conn,"SET CHARACTER SET utf8");
mysqli_query($conn,"SET COLLATION_CONNECTION = 'utf8_general_ci' ");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    //print_r("not connected");
}
else{
    //print_r("connected");
}

//print_r("ccsfdsfd");
