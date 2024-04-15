<?php

namespace Login\classes;

use PDO;
use PDOException;

session_start();

class DBConnection {
    private static $instance = null;
    private $db;

    private function __construct() {
        $host = 'localhost';
        $dbname = 'login_3';
        $username = 'root';
        $password = ''; // Vul hier het wachtwoord voor de databasegebruiker in

        try {
            $this->db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit("Database Error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->db;
    }
}

class User {
    private $db;

    public function __construct(PDO $dbConnection) {
        $this->db = $dbConnection;
    }

    public function registerUser($username, $password, $role = 'gebruiker') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
    
        if ($stmt === false) {
            $errorInfo = $this->db->errorInfo();
            // Log or handle the error
            echo "Error preparing statement: " . $errorInfo[2];
            return false;
        }
    
        try {
            $stmt->execute([$username, $hashedPassword, $role]);
            return true;
        } catch (PDOException $e) {
            // Log error or handle it as needed
            echo "Error executing statement: " . $e->getMessage();
            return false;
        }
    }
    

    public function loginUser($username, $password) {
        $sql = "SELECT password, role FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            return true;
        } else {
            return false;
        }
    }

    public function getUserInfo() {
        if (!$this->isLoggedin()) {
            return false;
        }

        $sql = "SELECT username FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    public function isLoggedin() {
        return isset($_SESSION['username']);
    }

    public function showUser() {
        if ($userInfo = $this->getUserInfo()) {
            echo "Username: " . $userInfo['username'];
        } else {
            echo "No user information available.";
        }
    }

    public function logout() {
        session_unset();
        session_destroy();

        header('Location: index.php');
        exit;
    }
}

// Gebruik
$db = DBConnection::getInstance();
$user = new User($db);

?>
