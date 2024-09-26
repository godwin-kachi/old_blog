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
    echo json_encode(["message" => "Database connection failed.", "status" => 3]);
    return;
}

$user = new User($conn);


// get user_id of user to be edited
$data = json_decode(file_get_contents("php://input"));


// ===== Auth Gate Check =========================
if (!authGateCheck($data)) {
    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 30]);
    return;
}

// Session check
$user->user_id = scrubUserCode2($data);
$userStmt = $user->getUser();
$userData = $userStmt['output']->fetch(PDO::FETCH_ASSOC);


if(!authGateCheck2($userData, $data)){
    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
    return;
}

if($userData['email'] != $data->email) {
    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "User Not authorised to logout this user with this email.","result" => false, "status" => 32]);
    return;
}

// ===== Auth Gate Check ends here ===============

// Check for valid email
if (empty($data->email) || $data->email == null || trim($data->email) == '') {

    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "Please provide a valid email", "status" => 2]);
    return;
}

// Sanitize and set user property values
$user->email = cleanData($data->email);

// Handle string return
if ($user->userLogout()) {

    // set response code - 200 ok
    http_response_code(200);
    // tell the user
    echo json_encode(["message" => "User logged out successfully", "result"=>true, "status" => 1]);
    return;

}  else {
    // if user does not exist

    // set response code - 503 service unavailable
    http_response_code(400);
    // tell the user
    echo json_encode(["message" =>"User logout FAILED. Try again.", "result"=> false, "status" => 4]);
    return;

}
