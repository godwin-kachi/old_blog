<?php
include '../config/autoloader.php';

// required headers
header("Access-Control-Allow-Origin:" . $configx["dbconnx"]["ORIGIN"]);
header("Content-Type:" . $configx["dbconnx"]["CONTENT_TYPE"]);
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]['GET_METHOD']);
header("Access-Control-Max-Age:" . $configx["dbconnx"]['MAX_AGE']);
header("Access-Control-Allow-Headers:" . $configx["dbconnx"]['ALLOWED_HEADERS']);

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

// read user id will be here
$user_id = null;

if (!empty($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {


    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->user_id)) {
        $user_id = $data->user_id;
    }
}


if ((empty($user_id) || $user_id == null || !is_numeric($user_id) || trim($user_id) == '')) {
    // No valid user id provided

    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(["message" => "Plaese provide a valid user ID"]);

    return;
}

// query users
$user->user_id = $user_id;

$stmt = $user->getUser();
// var_dump($stmt);
// return;

// check if more than 0 record found
if ($stmt["outputStatus"] == 1000) {
     
    $result = $stmt["output"]->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        // set response code - 200 OK
        http_response_code(404);

        // show subjects data in json format
        echo json_encode(["message" => "No subject found with this ID:$user_id", "status"=>0]);

        return;
    }

    // Remove password   
    unset($result['password']);
  
    // set response code - 200 OK
    http_response_code(200);
    // show subjects data in json format
    echo json_encode(["result"=>$result, "status"=>1]);
    return;
} 
elseif ($stmt['outputStatus'] == 1200) {
    // set response code - 200 OK
    http_response_code(200);

    // show subjects data in json format
    echo json_encode(["message" => "No subject found with this ID:$user_id", "status"=>22]);
    return;
}
elseif ($stmt['outputStatus'] == 1400) {
    //  // set response code - 503 service unavailable
    http_response_code(503);
    // tell the user
    echo json_encode( errorDiag($stmt['output']) );
    return;
}
else{
    // no subjects found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the subject no products found
    echo json_encode(
        ["message" => "Something went wrong. Not able to fetch subject."]
    );
}