<?php

class userModel
{
    private $conn;

    public function __construct($conn)
    {

        $this->conn = $conn;
    }


    public function createUser($full_name, $email, $username, $password)
    {
        $stmt = $this->conn->prepare("INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $full_name, $email, $username, $password);

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    public function login($username)
    {
        $stmt = $this->conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);

        $result = $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }
}
