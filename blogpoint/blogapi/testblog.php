<?php


// required headers
header("Access-Control-Allow-Origin:" . $configx["dbconnx"]["ORIGIN"]);
header("Content-Type:" . $configx["dbconnx"]["CONTENT_TYPE"]);
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]["POST_METHOD"]);
header("Access-Control-Max-Age:$" . $configx["dbconnx"]["MAX_AGE"]);
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

$blog = new Blog($conn);

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


$stmt = $blog->getAllBlogs();

// check if more than 0 record found
if ($stmt["outputStatus"] == 1000) {
     
    $result = $stmt["output"]->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($result);
    // return;
    
    if (count($result) == 0 || !$result) {
        // set response code - 200 OK
        http_response_code(404);
        // show assignments data in json format
        echo json_encode(["message" => "No assignment found.", "status"=>1]);
        return;
    }

  
    // set response code - 200 OK
    http_response_code(200);
    // show blogs data in json format
    echo json_encode(["result"=>$result, "status"=>1]);
    return;
} 
elseif ($stmt['outputStatus'] == 1400) {
    // no blogs found will be here
      // set response code - 404 Not found
      http_response_code(404);
      // tell the blog no products found
      echo json_encode(errorDiag($stmt['output']));
    return;
}
else{
    // no blogs found will be here

    // set response code - 404 Not found
    http_response_code(404);
    // tell the blog no products found
    echo json_encode(["message" => "Something went wrong. Not able to fetch blog."]);
}