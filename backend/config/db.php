<?php
$db_server="localhost";
$db_user="root";
$db_password="";
$db_name="thunderkicks";

$conn="";

try{
    $conn=mysqli_connect($db_server ,$db_user, $db_password, $db_name );
}
catch(mysqli_sql_exception){
    echo"Not Connected";

}
   if($conn){
    echo "ss";
   }

?>