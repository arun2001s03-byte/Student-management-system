<?php
$conn = mysqli_connect("localhost", "root", "", "arun");
if(!$conn) 
{
    die("Connection failed: " . mysqli_connect_error());
}

?>