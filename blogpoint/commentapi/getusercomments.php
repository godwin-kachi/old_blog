<?php
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]["POST_METHOD"]);

// initialize object
$db = new Database($configx);
$conn = $db->getConnection();

// Check connection
if ($conn == null) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed.", "status" => 7]);
    return;
}

$blog = new Blog($conn);

// read blog id will be here
$user_id = $param_id;


if ((empty($user_id) || $user_id == null || !is_numeric($user_id) || trim($user_id) == '')) {
    // No valid blog id provided

    // set response code - 404 Not found
    http_response_code(404);
    // tell the blog no products found
    echo json_encode(["message" => "Plaese provide a valid User ID."]);
    return;
}

// query blogs
$blog->user_id = $user_id;

/*
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

if (!$userData) {
    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 32]);
    return;
}

// ===== Auth Gate Check 2  =====================

if(!authGateCheck2($userData, $data)){
    // set response code - 503 service unavailable
    http_response_code(401);
    // tell the user
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
    return;
}

// ===== Auth Gate Check ends here ===============

*/

 

    $blog_stmt = $blog->getUserBlogs();

// check if more than 0 record found
if ($blog_stmt["outputStatus"] == 1000) {
     
    $result = $blog_stmt["output"]->fetchAll(PDO::FETCH_ASSOC);
   

    if (!$result) {
        // set response code - 200 OK
        http_response_code(404);
        // show subjects data in json format
        echo json_encode(["message" => "No blogs found for this user", "status"=>0]);
        return;
    }

  
    // set response code - 200 OK
    http_response_code(200);
    // show subjects data in json format
    echo json_encode(["result"=>$result, "status"=>1]);
    return;
} 
elseif ($blog_stmt['outputStatus'] == 1200) {
    // no subjects found will be here
    echo json_encode(errorDiag($blog_stmt['output']));
    return;
}
else{
    // no subjects found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the subject no products found
    echo json_encode(["message" => "Something went wrong. Not able to fetch subject."]);
}