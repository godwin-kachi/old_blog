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

// Check for valid user email
if (empty($data->email) || $data->email == null || trim($data->email) == "") {
    // set response code - 403 forbidden
    http_response_code(403);
    // tell the user
    echo json_encode(["message" => "Please provide your valid email", "status" => 2]);
    return;
}


// clean and set user_id properties of user to be edited
$user->email = cleanData($data->email);

// Retrieve the user details
$user_stmt = $user->searchUser($user->email, "email");

// Handle user retrieval errors
if ($user_stmt['outputStatus'] == 1000) {
        
    $user_to_update = $user_stmt['output']->fetch(PDO::FETCH_ASSOC);

    unset($user_to_update['password']);

    // If user does not exist
    if (!$user_to_update || empty($user_to_update)) {
        // set response code - 404 not found
        http_response_code(404);
        // tell the user
        echo json_encode(["message" => "No user found with this email.", "status" => 0]);
        return;
    }

    // ===== Send evcode to user email =================

        $uevc = $user_to_update['user_code'];
        $to = $user->email;
        $subject = "Email Verification Sent";
        $message = "<p>Your Email Verification Code has been sent to your mail</p>";
        $message .= "<p>Your evcode is $uevc </p>";
        $message .= "<p>Customercare Manager </p>";
        $message .= "<p>GlobeTrack Services </p>";

        $mailSent = mail($to, $subject, $message);


    // ======== send mail ends here =================

         // For Development test purposes only: Remove 
         http_response_code(404);
         // tell the user
         echo json_encode(["message"=>"Your Email verification code has been sent to your email.", "evcode" => $uevc, "mail_sent"=>$mailSent, "status" => 0, "mail_content"=>$message]);
         return;
    

} elseif ($user_stmt['outputStatus'] == 1400) {
        
    errorDiag($user_stmt['output']);
    return;
        
} else {
        
    // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode(["message" => "Unable to update user. Please try again.", "status" => 7]);
    return;
        
}
