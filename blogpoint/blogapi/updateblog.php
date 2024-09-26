<?php
// required headers
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]["PUPA_METHOD"]);


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

// Declare blog_stmt property
//$updateStatus = null;
//$blog_to_update = null;

// get blog_id of user to be edited
$data = json_decode(file_get_contents("php://input"));


 // ===== Auth Gate Check =========================
 if (!authGateCheck($data)) {
    // set response code - 503 service unavailable
    http_response_code(401);
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 30]);
    return;
}

// Session check
$user->user_id = cleanData(scrubUserCode2($data));

if (!is_numeric($user->user_id)) {
     // set response code - 503 service unavailable
     http_response_code(401);
     echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
     return;
}

$userStmt = $user->getUser();
$userData = $userStmt['output']->fetch(PDO::FETCH_ASSOC);

// ===== Auth Gate Check 2  =====================

if (!$userData || !authGateCheck2($userData, $data)) {
    // set response code - 503 service unavailable
    http_response_code(401);
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 32]);
    return;
}

// ===== Auth Gate Check ends here ===============


// ============ Prepare to Update blog =========================
// Check for valid blog_id
if (empty($data->blog_id) || !is_numeric($data->blog_id) || intval($data->blog_id) <= 0) {
    // set response code - 403 forbidden
    http_response_code(403);
    echo json_encode(["message" => "Please provide a valid blog id", "status" => 2]);
    return;
}


// set blog_id property of blog to be edited
$blog->blog_id = cleanData($data->blog_id);

// Retrieve the blog details
$blog_stmt = $blog->getBlog();

// Handle blog retrieval errors
if ($blog_stmt['outputStatus'] == 1000) {
        
    $blog_to_update = $blog_stmt['output']->fetch(PDO::FETCH_ASSOC);
        

    // If blog does not exist
    if (!$blog_to_update || empty($blog_to_update)) {
        // set response code - 404 not found
        http_response_code(404);
        echo json_encode(["message" => "No blog found with this ID.", "status" => 0]);
        return;
    }


    // ===== Authorisation Gate Check  =====================

if(isDriver($userData) || isPilot($userData) || $blog_to_update['user_id'] != $$userData['user_id']){
    // set response code - 404 not found
    http_response_code(404);
    echo json_encode(["message" => "No blog found with this ID.", "status" => 0]);
    return;
}





// ===== Authorisation Gate Check =====================

    
        // Update blog details
        $blog->title = empty($data->title) ? $blog_to_update['title'] : cleanData($data->title);
        $blog->content = empty($data->content) ? $blog_to_update['content'] : cleanData($data->content);
        $blog->image = empty($data->image) ? $blog_to_update['image'] : cleanData($data->image);

        $blog->user_id = empty($data->user_id) ? $blog_to_update['user_id'] : cleanData($data->user_id);
        $blog->tags = empty($data->tags) ? $blog_to_update['tags'] : cleanData($data->tags);
        $blog->categories = empty($data->categories) ? $blog_to_update['categories'] : cleanData($data->categories);
        $blog->click_count = empty($data->click_count) ? $blog_to_update['click_count'] : cleanData($data->click_count);
        $blog->active = empty($data->active) ? $blog_to_update['active'] : cleanData($data->active);

        $blog->created_at = empty($data->created_at) ? $blog_to_update['created_at'] : cleanData($data->created_at);
        
        // update the blog
        $updateStatus = $blog->updateBlog();
            
  
    // Check update status
    if ($updateStatus['outputStatus'] == 1000 && $updateStatus['output'] == true) {
            
        // set response code - 200 ok
        http_response_code(200);
        echo json_encode(["message" => "blog was updated successfully.", "status" => 1]);
        return;
            
    } 
    elseif ($updateStatus['outputStatus'] == 1200 && $updateStatus['output'] == false) {

        // set response code - 201 created
        http_response_code(201);
        echo json_encode(["message" => "blog was NOT updated. Please try again.", "status" => 0]);
        return;
   }
    elseif ($updateStatus['outputStatus'] == 1400) {
            
        // set response code - 503 service unavailable
        http_response_code(503);
        echo json_encode(errorDiag($updateStatus['output']));
        return;
            
    } else {
            
        // set response code - 503 service unavailable
        http_response_code(503);
        echo json_encode(["message" => "Unable to update blog. Please try again.", "status" => 5]);
        return;
            
    }
} elseif ($blog_stmt['outputStatus'] == 1200) {
     // set response code - 503 service unavailable
     http_response_code(503);
     echo json_encode(errorDiag($blog_stmt['output']));
    return;
        
} else {
        
    // set response code - 503 service unavailable
    http_response_code(503);
    echo json_encode(["message" => "Unable to update blog. Please try again.", "status" => 6]);
    return;
        
}
