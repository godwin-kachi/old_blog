<?php
include '../config/autoloader.php';

// required headers
header("Access-Control-Allow-Origin:" . $configx["dbconnx"]["ORIGIN"]);
header("Content-Type:" . $configx["dbconnx"]["CONTENT_TYPE"]);
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]["POST_METHOD"]);
header("Access-Control-Max-Age:" . $configx["dbconnx"]["MAX_AGE"]);
header("Access-Control-Allow-Headers:" . $configx["dbconnx"]["ALLOWED_HEADERS"]);

// initialize object
$db = new Database($configx);
$conn = $db->getConnection();

// Check connection
if ($conn == null) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed.", "status" => 7]);
    return;
}

$user = new User($conn);


// get user_id of user to be edited
$data = json_decode(file_get_contents("php://input"));

// Check for valid user_id
if (empty($data->user_id) || !is_numeric($data->user_id) || intval($data->user_id) <= 0) {
    // set response code - 403 forbidden
    http_response_code(403);
    // tell the user
    echo json_encode(["message" => "Please provide a valid user id", "status" => 2]);
    return;
}

// set user_id property of user to be edited
$user->user_id = cleanData($data->user_id);

// Retrieve the user details
$user_stmt = $user->getUser();

// Handle user retrieval errors
if ($user_stmt['outputStatus'] == 1000) {
        
    $user_to_update = $user_stmt['output']->fetch(PDO::FETCH_ASSOC);
        
    // If user does not exist
    if (!$user_to_update || empty($user_to_update)) {
        // set response code - 404 not found
        http_response_code(404);
        // tell the user
        echo json_encode(["message" => "No user found with this ID.", "status" => 0]);
        return;
    }


        // Update user details
        $user->firstname = empty($data->firstname) ? $user_to_update['firstname'] : cleanData($data->firstname);
        $user->password = empty($data->password) ? $user_to_update['password'] : cleanData(password_hash($data->password, PASSWORD_DEFAULT));
        $user->active = empty(intval($data->active)+1) ? $user_to_update['active'] : cleanData($data->active);
        $user->role_id = empty(intval($data->role_id)+1) ? $user_to_update['role_id'] : cleanData($data->role_id);

        // update the user
        $updateStatus = $user->updateUser();
            
    // Check update status
    if ($updateStatus['outputStatus'] == 1000 && $updateStatus['output'] == true) {
            
        // set response code - 200 ok
        http_response_code(200);
        // tell the user
        echo json_encode(["message" => "User was updated successfully.", "status" => 1]);
        return;
            
    } elseif ($updateStatus['outputStatus'] == 1200  && $updateStatus['output'] == false) {
            
        // set response code - 200 ok
        http_response_code(202);
        // tell the user
        echo json_encode(["message" => "User updated FAILED. Try again.", "status" => 7]);
        return;
            
    } elseif ($updateStatus['outputStatus'] == 1400) {
            
        errorDiag($updateStatus['output']);
        return;
            
    } else {
            
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(["message" => "Unable to update user. Please try again.", "status" => 5]);
        return;
            
    }
} elseif ($user_stmt['outputStatus'] == 1200) {
        
    errorDiag($user_stmt['output']);
    return;
        
} else {
        
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode(["message" => "Unable to update user. Please try again.", "status" => 6]);
    return;
        
}
