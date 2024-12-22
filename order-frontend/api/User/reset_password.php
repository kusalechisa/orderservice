<?php
// get database connection
include_once '../config/database.php';

// instantiate user object
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// get email and new password from POST request
$user->email = isset($_POST['email']) ? $_POST['email'] : die(json_encode(["status" => false, "message" => "Email is required"]));
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : die(json_encode(["status" => false, "message" => "New password is required"]));

// Check if email exists in the database
if (!$user->isAlreadyExist()) {
    echo json_encode(["status" => false, "message" => "Email not found."]);
    exit;
}

// Validate new password (e.g., check minimum length)
if (strlen($new_password) < 6) {
    echo json_encode(["status" => false, "message" => "Password must be at least 6 characters long."]);
    exit;
}

// Hash the new password before saving it
$password_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Update the password in the database
$query = "UPDATE users SET password = :password WHERE email = :email";
$stmt = $db->prepare($query);

// bind values
$stmt->bindParam(':email', $user->email);
$stmt->bindParam(':password', $password_hash);

// execute query to update the password
if ($stmt->execute()) {
    echo json_encode(["status" => true, "message" => "Password updated successfully."]);
} else {
    echo json_encode(["status" => false, "message" => "Unable to update password. Please try again later."]);
}
?>
