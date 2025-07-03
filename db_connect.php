<?php 
$DBConnect = mysqli_connect("localhost", "root", "", "stationery_shop");
if (mysqli_connect_errno()) {
    die("Database Connection failed: " . mysqli_connect_error());
}
?>