<?php
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]['POST_METHOD']);

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

// get posted data
$data = json_decode(file_get_contents("php://input"));

/*

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
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 33]);
    return;
}

// ===== Auth Gate Check ends here ===============

*/


// make sure data is not empty
if (
    !empty($data->title) || !empty($data->content) || !empty($data->user_id) || !empty($data->image) 
) {

    // Sanitize & set blog property values
    $blog->title = cleanData($data->title);
    $blog->content = cleanData($data->content);
    $blog->user_id = cleanData($data->user_id);
    $blog->image = cleanData($data->image);
    $blog->tags = empty($data->tags) ? NULL : cleanData($data->tags);
    $blog->categories = empty($data->categories) ? NULL : cleanData($data->categories);

 
    // create the assignment
    $newblog = $blog->createBlog();


    if ($newblog['outputStatus'] == 1000 && $newblog['output'] == true) {

        // set response code - 201 created
        http_response_code(201);
        // tell the blog
        echo json_encode(["message" => "blog was created successfully", "status" => 1]);
        return;
    }
    elseif ($newblog['outputStatus'] == 1200 && $newblog['output'] == false) {

         // set response code - 201 created
         http_response_code(201);
         // tell the blog
         echo json_encode(["message" => "blog was NOT created. Please try again.", "status" => 0]);
         return;
    }
    elseif ($newblog['outputStatus'] == 1400) {

        errorDiag($newblog['output']);
        return;
    }
    else {
        // set response code - 200 ok
        http_response_code(400);
        // tell the blog
        echo json_encode(["message" => $newblog['output'], "status" => 2]);
        return;
    }
    
} else {

    // tell the blog data is incomplete

    // set response code - 400 bad request
    http_response_code(400);

    // tell the blog
    echo json_encode(["message" => "Unable to create blog. Fill all fields.", "status" => 3]);
    return;

}


