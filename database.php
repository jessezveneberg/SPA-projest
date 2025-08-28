<?php

$mysqli=false;
function connectDB(){
global $conn;
$conn=new mysqli("localhost","root","","tzpidp");

$conn->query("SET NAMES 'utf8'");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
}


?>
