<?php
// User model for interacting with the database

class User {
    private $db;

    public function __construct() {
        try {
            // Database connection
            $this->db = new PDO('mysql:host=localhost;dbname=delivery;charset=utf8', 'root', '');
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Set error mode to exception
        } catch (PDOException $e) {
            // Handle database connection error
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Register a new user with role
    public function register($username, $email, $password, $role) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Prepare SQL query to insert user data with role
            $stmt = $this->db->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
            return $stmt->execute([$username, $email, $hashedPassword, $role]);
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log($e->getMessage());
            return false;  // Return false if the operation fails
        }
    }

    // Login user by verifying email and password
    public function login($email, $password) {
        try {
            // Fetch user by email
            $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Check password match
            if ($user && password_verify($password, $user['password'])) {
                return $user;  // User found and password verified
            }
            return null;  // Invalid email or password
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log($e->getMessage());
            return null;  // Return null if the operation fails
        }
    }

    // Get user by ID
    public function getUserById($userId) {
        try {
            $stmt = $this->db->prepare('SELECT id, username, email, role FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);  // Return user data or null if not found
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log($e->getMessage());
            return null;
        }
    }

    // Get all users
    public function getAllUsers() {
        try {
            $stmt = $this->db->prepare('SELECT id, username, email, role FROM users');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Return array of all users
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log($e->getMessage());
            return null;
        }
    }
}
?>
