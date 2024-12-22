<?php
 
// get database connection
include_once '../config/database.php';
 
// instantiate user object
include_once '../objects/user.php';
 
$database = new Database();
$db = $database->getConnection();
 
$user = new User($db);
 
// set user property values
$user->name = isset($_POST['name']) ? $_POST['name'] : die(json_encode(["status" => false, "message" => "Name is required"]));
$user->email = isset($_POST['email']) ? $_POST['email'] : die(json_encode(["status" => false, "message" => "Email is required"]));
$user->password = isset($_POST['password']) ? base64_encode($_POST['password']) : die(json_encode(["status" => false, "message" => "Password is required"]));

// Set created_at to current datetime
$user->created_at = date('Y-m-d H:i:s');
 
// create the user
if ($user->signup()) {
    $user_arr = array(
        "status" => true,
        "message" => "Successfully signed up!",
        "id" => $user->id,
        "name" => $user->name,
        "email" => $user->email,
        "created_at" => $user->created_at
    );
} else {
    $user_arr = array(
        "status" => false,
        "message" => "Email already exists!"
    );
}
 
// Return JSON response
header('Content-Type: application/json');
echo json_encode($user_arr);
?>
