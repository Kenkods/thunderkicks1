<?php 
class userModel{
   private $conn;

   public function __construct() {
      
       $this->conn = include_once(__DIR__ . '/../config/db.php');
   }


    public createUser($full_name,$email,$username,$password){
        $stmt=$this->conn->prepare("INSERT INTO users (fullname, email, username, password) VALUES (?, ?, ? ?)");
        $stmt->bind_param("sss", $full_name, $email, $username, $password);

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    


}
?>