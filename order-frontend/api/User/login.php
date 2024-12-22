<?php
// get database connection
include_once '../config/database.php';

// instantiate user object
include_once '../objects/user.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

// get email and password from POST request
$user->email = isset($_POST['email']) ? $_POST['email'] : die(json_encode(["status" => false, "message" => "Email is required"]));
$user->password = isset($_POST['password']) ? base64_encode($_POST['password']) : die(json_encode(["status" => false, "message" => "Password is required"]));

// attempt to login
$stmt = $user->login();

// check if any user is found with the credentials
if ($stmt->rowCount() > 0) {
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_arr = array(
        "status" => true,
        "message" => "Login successful",
        "id" => $user_data['id'],
        "email" => $user_data['email'],
        "created_at" => $user_data['created_at']
    );
    //echo json_encode($user_arr);
    echo json_encode(["status" => true, "message" => "Login successful here"]);
} else {
    $user_arr = array(
        "status" => false,
        "message" => "Invalid email or password. Please try again."
    );
    echo json_encode($user_arr);
}
?>
