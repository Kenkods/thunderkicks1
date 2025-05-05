<?php
$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "thunder";


$conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);

// Check if connection was successful
if ($conn) {
    echo "Connected successfully!";
} else {
    echo "Connection failed: " . mysqli_connect_error();
   
}
return $conn;
?>
