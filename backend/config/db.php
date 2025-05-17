<?php
function getConnection(){
$db_server = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "thunders";


$conn = mysqli_connect($db_server, $db_user, $db_password, $db_name);

// Check if connection was successful
if (!$conn) {
    echo "Connection failed: " . mysqli_connect_error();
   
} 
return $conn;
}
?>
