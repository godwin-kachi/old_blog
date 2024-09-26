<?php
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]["GET_METHOD"]);

// initialize object
$db = new Database($configx);
$conn = $db->getConnection();

// Check connection
if ($conn == null) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed.", "status" => 7]);
    return;
}

$comment = new Comment($conn);

// read comment id will be here
$comment_id = $param_id;


if ((empty($comment_id) || $comment_id == null || !is_numeric($comment_id) || trim($comment_id) == '')) {
    // No valid comment id provided

    // set response code - 404 Not found
    http_response_code(404);
    // tell the comment no products found
    echo json_encode(["message" => "Plaese provide a valid comment ID."]);
    return;
}


// Call method depending on which parameter is provided
    // query comments
    $comment->comment_id = $comment_id;

    $comment_stmt = $comment->getcomment();

// check if more than 0 record found
if ($comment_stmt["outputStatus"] == 1000) {
     
    $result = $comment_stmt["output"]->fetch(PDO::FETCH_ASSOC);
   

    if (!$result) {
        // set response code - 200 OK
        http_response_code(404);
        // show subjects data in json format
        echo json_encode(["message" => "No subject found with this ID:$comment_id", "status"=>0]);
        return;
    }

  
    // set response code - 200 OK
    http_response_code(200);
    // show subjects data in json format
    echo json_encode(["result"=>$result, "status"=>1]);
    return;
} 
elseif ($comment_stmt['outputStatus'] == 1200) {
    // no subjects found will be here
    echo json_encode(errorDiag($comment_stmt['output']));
    return;
}
else{
    // no subjects found will be here

    // set response code - 404 Not found
    http_response_code(404);

    // tell the subject no products found
    echo json_encode(["message" => "Something went wrong. Not able to fetch subject."]);
}