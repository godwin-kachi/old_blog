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
    echo json_encode(["message" => "Database connection failed.", "status" => 5]);
    return;
}

$user = new User($conn);


// get user_id of user to be edited
$data = json_decode(file_get_contents("php://input"));

// Check for valid email
if (empty($data->email) || $data->email == null || trim($data->email) == '') {

    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "Please provide a valid email", "status" => 4]);
    return;
}

// Check for valid password
if (empty($data->password) || $data->password == null || trim($data->password) == '') {

    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "Please provide a valid password", "status" => 3]);
    return;

}

// Sanitize and set email property
$user->email = cleanData($data->email);
// Sanitize provided password
$data->password2 = cleanData($data->password);

// Check if user with this login details exists
$login_stmt = $user->userLogin();

// Check if user exists
if ($login_stmt['outputStatus'] == 1000) {
        
    $user_to_login = $login_stmt['output']->fetch(PDO::FETCH_ASSOC);

    // If user does not exist
    if (!$user_to_login || empty($user_to_login)) {
        // set response code - 404 not found
        http_response_code(404);
        // tell the user
        echo json_encode(["message" => "Wrong email or Password.", "status" => 0]);
        return;
    }

      
    // confirm password
    $passCheck = $user->verifyPass($data->password, $user_to_login['password']);

    // If password check is false, update the user ad exit
    if (!$passCheck) {
        // set response code - 404 not found
        http_response_code(400);
        // tell the user
        echo json_encode(["message" => "Wrong email or pasword. Try again.", "status" => 2]);
        return;
    }

   

    // Else login passes, generate session code and deliver user data
    $user->user_id = $user_to_login['user_id'];
    $genCheck = $user->reGenerateUserCode();

    // Update session
    $user_to_login['user_code'] = prepareUserCode($user->user_code);
    $lost_code = prepareUserCode2();
    $sest_code = prepareUserCode2();

    // Scrub password
    unset($user_to_login['password']);
    
     // set response code - 404 not found
     http_response_code(200);
     // tell the user
     echo json_encode(["message" => "Login successful.", "globetk_usc"=>$user_to_login['user_code'], "globetk_lsc"=> $lost_code , "globetk_ssc"=>$sest_code, "status" => 1]);
     return;

    
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
    echo json_encode(["message" => "Unable to update user. Please try again.", "status" => 6]);
    return;
        
}
