<?php
include '../config/autoloader.php';

// required headers
header("Access-Control-Allow-Origin:" . $configx["dbconnx"]["ORIGIN"]);
header("Content-Type:" . $configx["dbconnx"]["CONTENT_TYPE"]);
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]['POST_METHOD']);
header("Access-Control-Max-Age:" . $configx["dbconnx"]['MAX_AGE']);
header("Access-Control-Allow-Headers:" . $configx["dbconnx"]['ALLOWED_HEADERS']);


// initialize object
$db = new Database($configx);
$conn = $db->getConnection();

// Check connection
if ($conn == null) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed.", "status" => 2]);
    return;
}

$user = new User($conn);

// get posted data
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
$user->user_id = cleanData(scrubUserCode2($data));

if (!is_numeric($user->user_id)) {
     // set response code - 503 service unavailable
     http_response_code(401);
     // tell the user
     echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
     return;
}

$userStmt = $user->getUser();
$userData = $userStmt['output']->fetch(PDO::FETCH_ASSOC);


if(!authGateCheck2($userData, $data)){
    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
    return;
}

// ===== Auth Gate Check ends here ===============

// ===== Authorisation Gate Check ===============

if(isDriver($userData)){
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "User Not Authorsied to carry out the action.","result" => false, "status" => 40]);
    return;
}

// ===== Authorisation Gate Check ends here ===============


// make sure data is not empty
if (!empty($data->firstname) || !empty($data->email)) {

    // Check if the email already exists
    $user_stmt = $user->searchUser($data->email, "email");

    if ($user_stmt['outputStatus'] == 1000) {
        
        $userExits = $user_stmt['output']->fetch(PDO::FETCH_ASSOC);

        if($userExits) {

            // set response code - 200 ok
            http_response_code(400);
            // tell the user
            echo json_encode(["message" => "email already exists. Please choose another one.", "status" => 0]);
            return;

        }


        // Sanitize & set user property values
        $user->firstname = cleanData($data->firstname);
        $user->email = cleanData($data->email);

        // Generate and set user password
        $data->password = $user->genPass();
        $user->password = $data->password;

        // create the user
        $new_user = $user->createUser();

        // var_dump($new_user);
        // return;

        if ($new_user['outputStatus'] == 1000 || $new_user['output'] == true) {

            // set response code - 201 created
            http_response_code(201);
            echo json_encode(["message" => "New user was created successfully and your password is $data->password", "status" => 1]);
            return;
            
        }
        elseif ($new_user['outputStatus'] == 1200 || $new_user['output'] == false) {

            // set response code - 201 created
            http_response_code(201);
            echo json_encode(["message" => "New user creation FAILED.Try again.", "status" => 1]);
            return;
            
        }
        elseif ($new_user['outputStatus'] == 1400) {

             // set response code - 503 service unavailable
             http_response_code(503);
             // tell the user
             echo json_encode( errorDiag($new_stmt['output']) );
             return;
        }
        else {
            // set response code - 200 ok
            http_response_code(400);

            // tell the user
            echo json_encode(["message" => $new_user['output'], "status" => 3]);
            return;
        }

    }elseif ($user_stmt['outputStatus'] == 1200) {

         // set response code - 503 service unavailable
        http_response_code(503);
        // tell the user
        echo json_encode( errorDiag($user_stmt['output']) );
        return;
    }
    else {
        // set response code - 200 ok
        http_response_code(400);
        // tell the user
        echo json_encode(["message" => "Network issues. Try again.", "status" => 4]);
        return;
    }

} else {

    // set response code - 400 bad request
    http_response_code(400);
    // tell the user
    echo json_encode(["message" => "Unable to create user. Fill all fields.", "status" => 5]);
    return;

}


