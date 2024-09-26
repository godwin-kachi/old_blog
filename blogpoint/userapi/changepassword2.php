<?php
// All admin can access this in order to change the password of any user passwordlessly.
// User must be logged in and on success, a new pasword will be auto-generated and sent to thier email.

header("Access-Control-Allow-Methods:" . $configx["dbconnx"]["POST_METHOD"]);

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

$user->user_id = cleanData($param_id);

if(empty($user->user_id) || is_null($user->user_id) || !is_numeric($user->user_id)){

    // set response code - 503 service unavailable
    http_response_code(401);
    echo json_encode(["message" => "Please provide a valid user ID to be updated.","result" => false, "status" => 30]);
    return;

}


// ===== Auth Gate Check =========================

$pilot = new Pilot($conn);

if (!authGateCheck($data)) {
    // set response code - 503 service unavailable
    http_response_code(401);
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 30]);
    return;
}

// clean and set user_id properties of user to be edited
$pilot->user_id = cleanData(scrubUserCode2($data));

if (!is_numeric($pilot->user_id) || is_null($pilot->user_id) || intval($pilot->user_id) <= 0) {
     // set response code - 503 service unavailable
     http_response_code(401);
     echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
     return;
}

// Retrieve the user details
$pilot_stmt = $pilot->getUser();


// Handle user retrieval errors
if ($pilot_stmt['outputStatus'] == 1000) {
        
    $pilot_to_update = $pilot_stmt['output']->fetch(PDO::FETCH_ASSOC);

    // If pilot does not exist
    if (!$pilot_to_update || empty($pilot_to_update)) {
        // set response code - 404 not found
        http_response_code(404);
        echo json_encode(["message" => "No pilot found with this ID.", "status" => 0]);
        return;
    }


    if(!authGateCheck2($pilot_to_update, $data)){
        // set response code - 503 service unavailable
        http_response_code(401);
        echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
        return;
    }

 // ===== Auth Gate Check ends here ===============


    // clean and set user passsword property of user to be changed
    $data->new_pass = $user->genPass();
    $user->password = $data->new_pass;

    // update the user
    $updateStatus = $user->changePassword();
            
    // Check update status
    if ($updateStatus['outputStatus'] == 1000 && $updateStatus['output'] == true) {

        $mailSent = false;

        $whitelist = ['localhost', '127.0.0.1', '::1'];
        if (!in_array($_SERVER['SERVER_NAME'], $whitelist)) {

            $user_firstname = $user_to_update['firstname'];

            $to = $user_to_update['email'];
            $subject = "Your New Pawword";
            $message = "Good day $user_firstname, \r\n\n Your new password is $data->new_pass \r\n\n Your faithfully, \r\n CustomerCare";
            $headers = "From: customercare@bloservices.com \r\n Reply-to: customerercer@bloservices.com \r\n X-Mailer: PHP" . phpversion();

            $mailSent = mail($to, $subject, $message, $headers);

        }
            
        // set response code - 200 ok
        http_response_code(200);
        echo json_encode(["message" => "User password was updated successfully. Your new password is $data->new_pass", "mail_sent"=>$mailSent, "status" => 1]);
        return;
            
    } elseif ($updateStatus['outputStatus'] == 1200  && $updateStatus['output'] == false) {
            
        // set response code - 200 ok
        http_response_code(200);
        echo json_encode(["message" => "New password updated FAILED. Try again", "status" => 1]);
        return;
            
    } elseif ($updateStatus['outputStatus'] == 1400) {
            
         // set response code - 503 service unavailable
        http_response_code(503);
        echo json_encode( errorDiag($updateStatus['output']) );
        return;
            
    } else {
            
        // set response code - 503 service unavailable
        http_response_code(503);
        echo json_encode(["message" => "Unable to update user password. Please try again.", "status" => 6]);
        return;
            
    }
} elseif ($user_stmt['outputStatus'] == 1400) {
        
     // set response code - 503 service unavailable
     http_response_code(503);
     echo json_encode( errorDiag($user_stmt['output']) );
     return;
        
} else {
        
    // set response code - 503 service unavailable
    http_response_code(503);
    echo json_encode(["message" => "Unable to update user. Please try again.", "status" => 7]);
    return;
        
}
