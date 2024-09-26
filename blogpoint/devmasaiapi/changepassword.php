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
    echo json_encode(["message" => "Database connection failed.", "status" => 8]);
    return;
}

$user = new User($conn);

// get user_id of user to be edited
$data = json_decode(file_get_contents("php://input"));

// Check for valid user_id
if (empty($data->user_id) || $data->user_id == null || trim($data->user_id) == "" || !is_numeric($data->user_id)) {
    // set response code - 403 forbidden
    http_response_code(403);
    // tell the user
    echo json_encode(["message" => "Please provide a valid User ID", "status" => 3]);
    return;
}

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

    // clean and set user passsword property of user to be changed
    $data->new_pass = cleanData($user->genPass());
    $user->password = $data->new_pass;

    // update the user
    $updateStatus = $user->changePassword();
            
    // Check update status
    if ($updateStatus['outputStatus'] == 1000 && $updateStatus['output'] == true) {
            
        // set response code - 200 ok
        http_response_code(200);
        // tell the user
        echo json_encode(["message" => "User password was updated successfully. Your new password is $data->new_pass", "status" => 1]);
        return;
            
    } elseif ($updateStatus['outputStatus'] == 1200  && $updateStatus['output'] == false) {
            
        // set response code - 200 ok
        http_response_code(200);
        // tell the user
        echo json_encode(["message" => "New password updated FAILED. Try again", "status" => 1]);
        return;
            
    } elseif ($updateStatus['outputStatus'] == 1400) {
            
         // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode( errorDiag($updateStatus['output']) );
    return;
            
    } else {
            
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(["message" => "Unable to update user password. Please try again.", "status" => 6]);
        return;
            
    }
} elseif ($user_stmt['outputStatus'] == 1400) {
        
     // set response code - 503 service unavailable
     http_response_code(503);
     // tell the user
     echo json_encode( errorDiag($user_stmt['output']) );
     return;
        
} else {
        
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode(["message" => "Unable to update user. Please try again.", "status" => 7]);
    return;
        
}
