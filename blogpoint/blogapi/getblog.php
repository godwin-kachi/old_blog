<?php
include '../config/autoloader.php';

// required headers
header("Access-Control-Allow-Origin:" . $configx["dbconnx"]["ORIGIN"]);
header("Content-Type:" . $configx["dbconnx"]["CONTENT_TYPE"]);
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]["GET_METHOD"]);
header("Access-Control-Max-Age:" . $configx["dbconnx"]["MAX_AGE"]);
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

$blog = new Blog($conn);

// read blog id will be here
$blog_id = null;
$blog_stmt = null;

// $blog_id = $param_id;


// Update blog_id param if it exists
if (!empty($_GET['blog_id'])) {
    $blog_id = $_GET['blog_id'];
} else {

    // set response code - 404 Not found
    http_response_code(404);
    // tell the blog no products found
    echo json_encode(["message" => "Plaese provide a valid blog ID."]);
    return;
}


if ((empty($blog_id) || $blog_id == null || !is_numeric($blog_id) || trim($blog_id) == '')) {
    // No valid blog id provided

    // set response code - 404 Not found
    http_response_code(404);
    // tell the blog no products found
    echo json_encode(["message" => "Plaese provide a valid blog ID."]);
    return;
}


// Call method depending on which parameter is provided
    // query blogs
    $blog->blog_id = $blog_id;

    $blog_stmt = $blog->getBlog();

// check if more than 0 record found
if ($blog_stmt["outputStatus"] == 1000) {
     
    $result = $blog_stmt["output"]->fetch(PDO::FETCH_ASSOC);
   

    if (!$result) {
        // set response code - 200 OK
        http_response_code(404);
        // show subjects data in json format
        echo json_encode(["message" => "No subject found with this ID:$blog_id", "status"=>0]);
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