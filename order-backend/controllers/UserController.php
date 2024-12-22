<?php
// Handles API endpoints for user actions (login, register, profile, user management)
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // Register a new user
    public function register($data) {
        if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['role'])) {
            return ['status' => 400, 'message' => 'All fields (username, email, password, role) are required.'];
        }

        // Validate role
        if (!in_array($data['role'], ['admin', 'user'])) {
            return ['status' => 400, 'message' => 'Invalid role. Role must be either "admin" or "user".'];
        }

        try {
            $result = $this->userModel->register($data['username'], $data['email'], $data['password'], $data['role']);
            if ($result) {
                return ['status' => 201, 'message' => 'User registered successfully with role ' . $data['role'] . '.'];
            }
            return ['status' => 500, 'message' => 'Registration failed. Please try again later.'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => 'Internal Server Error.', 'error' => $e->getMessage()];
        }
    }

    // Login a user
    public function login($data) {
        if (empty($data['email']) || empty($data['password'])) {
            return ['status' => 400, 'message' => 'Email and password are required.'];
        }

        try {
            $user = $this->userModel->login($data['email'], $data['password']);
            if ($user) {
                return [
                    'status' => 200,
                    'message' => 'Login successful.',
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ]
                ];
            }
            return ['status' => 401, 'message' => 'Invalid credentials.'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => 'Internal Server Error.', 'error' => $e->getMessage()];
        }
    }

    // Get user profile by ID
    public function getProfile($userId) {
        if (empty($userId)) {
            return ['status' => 400, 'message' => 'User ID is required.'];
        }

        try {
            $user = $this->userModel->getUserById($userId);
            if ($user) {
                return [
                    'status' => 200,
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ]
                ];
            }
            return ['status' => 404, 'message' => 'User not found.'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => 'Internal Server Error.', 'error' => $e->getMessage()];
        }
    }

    // Get all users
    public function getAllUsers() {
        try {
            $users = $this->userModel->getAllUsers();
            if ($users) {
                return ['status' => 200, 'users' => $users];
            }
            return ['status' => 404, 'message' => 'No users found.'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => 'Internal Server Error.', 'error' => $e->getMessage()];
        }
    }

    // Get single user by ID
    public function getUser($userId) {
        if (empty($userId)) {
            return ['status' => 400, 'message' => 'User ID is required.'];
        }

        try {
            $user = $this->userModel->getUserById($userId);
            if ($user) {
                return [
                    'status' => 200,
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ]
                ];
            }
            return ['status' => 404, 'message' => 'User not found.'];
        } catch (Exception $e) {
            return ['status' => 500, 'message' => 'Internal Server Error.', 'error' => $e->getMessage()];
        }
    }
}
?>
