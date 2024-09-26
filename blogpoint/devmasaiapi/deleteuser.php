<?php
include '../config/autoloader.php';

// required headers
header("Access-Control-Allow-Origin: " . $configx["dbconnx"]["ORIGIN"]);
header("Content-Type: " . $configx["dbconnx"]["CONTENT_TYPE"]);
header("Access-Control-Allow-Methods: " . $configx["dbconnx"]["DEL_METHOD"]);
header("Access-Control-Max-Age: " . $configx["dbconnx"]["MAX_AGE"]);
header("Access-Control-Allow-Headers: " . $configx["dbconnx"]["ALLOWED_HEADERS"]);

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


 $data = json_decode(file_get_contents("php://input"));

//  Chceck for valid user id to delete
if (empty($data->user_id) || $data->user_id == null || !is_numeric($data->user_id)) {
    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "Please provide a valis user ID.","result" => false, "status" => 30]);
    return;
}


// ===== Auth Gate Check =========================
if (!authGateCheck($data)) {
    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 30]);
    return;
}

// ===== Auth Gate Check =========================


// Session check
$user->user_id = cleanData(scrubUserCode2($data));

if (!is_numeric($user->user_id)) {
     // set response code - 503 service unavailable
     http_response_code(401);
     // tell the user
     echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
     return;
}

$user_stmt = $user->getUser();

// Handle user retrieval errors
if ($user_stmt['outputStatus'] == 1000) {
        
    $user_to_delete = $user_stmt['output']->fetch(PDO::FETCH_ASSOC);


    // If user does not exist
    if (!$user_to_delete || empty($user_to_delete)) {
        // set response code - 404 not found
        http_response_code(404);
        // tell the user
        echo json_encode(["message" => "No user found with this ID.", "status" => 0]);
        return;
    }

     // ===== Auth Gate Check 2 ===============

     if(!authGateCheck2($user_to_delete, $data)){
        // set response code - 503 service unavailable
        http_response_code(401);
        // tell the user
        echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
        return;
    }

    // ===== Auth Gate Check 2 ends here ===============

    // ===== Authorisation Gate Check ===============

    if(isDriver($user_to_delete)){
        http_response_code(401);
        echo json_encode(["message" => "User Not Authorsied to carry out the action.","result" => false, "status" => 40]);
        return;
    }

    if(isPilot($user_to_delete) && $data->user_id > 0){
        http_response_code(401);
        echo json_encode(["message" => "User Not Authorsied to carry out the action.","result" => false, "status" => 40]);
        return;
    }

   
    // ===== Authorisation Gate Check ends here ===============


    // update the user
    $deleteStatus = $user->deleteUser();
        
                   
    // Check delete status
    if ($deleteStatus['outputStatus'] == 1000 && $deleteStatus['output'] == true) {
            
        // set response code - 200 ok
        http_response_code(200);
        // tell the user
        echo json_encode(["message" => "User was deleted successfully.", "status" => 1]);
        return;
            
    } elseif ($deleteStatus['outputStatus'] == 1200 && $deleteStatus['output'] == false) {
            
        // set response code - 200 ok
        http_response_code(200);
        // tell the user
        echo json_encode(["message" => "User was deleted FAILED. Try again.", "status" => 7]);
        return;
            
    } elseif ($deleteStatus['outputStatus'] == 1400) {
            
       // set response code - 503 service unavailable
 http_response_code(503);
 // tell the user
 echo json_encode( errorDiag($deleteStatus['output']) );
 return;  
            
    } else {
            
        // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode(["message" => "Unable to delete user. Please try again.", "status" => 5]);
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
    echo json_encode(["message" => "Unable to delete user. Please try again.", "status" => 6]);
    return;
        
}
