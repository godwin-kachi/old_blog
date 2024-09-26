<?php

header("Access-Control-Allow-Methods: " . $configx["dbconnx"]['POST_METHOD']);

// Initialize database connection
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

// Get posted data
$data = json_decode(file_get_contents("php://input"));

/*
// ===== Auth Gate Check =========================
if (!authGateCheck($data)) {
    http_response_code(401);
    echo json_encode(["message" => "User Not authenticated.", "result" => false, "status" => 30]);
    return;
}

// Session check
$user->user_id = cleanData(scrubUserCode2($data));

if (!is_numeric($user->user_id)) {
    http_response_code(401);
    echo json_encode(["message" => "User Not authenticated.", "result" => false, "status" => 31]);
    return;
}

$userStmt = $user->getUser();
$userData = $userStmt['output']->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    http_response_code(401);
    echo json_encode(["message" => "User Not authenticated.", "result" => false, "status" => 32]);
    return;
}

// ===== Auth Gate Check 2 =====================
if (!authGateCheck2($userData, $data)) {
    http_response_code(401);
    echo json_encode(["message" => "User Not authenticated.", "result" => false, "status" => 33]);
    return;
} */

// Make sure data is not empty
if (!empty($data->title) || !empty($data->content) || !empty($data->user_id) || !empty($data->image)) {

    // Sanitize & set blog property values
    $blog->title = cleanData($data->title);
    $blog->content = cleanData($data->content);
    $blog->user_id = cleanData($data->user_id);
    $blog->image = cleanData($data->image);
    $blog->tags = empty($data->tags) ? NULL : cleanData($data->tags);
    $blog->categories = empty($data->categories) ? NULL : cleanData($data->categories);

    // Create the assignment
    $newblog = $blog->createBlog();

    if ($newblog['outputStatus'] == 1000 && $newblog['output'] == true) {
        http_response_code(201);
        echo json_encode(["message" => "Blog was created successfully", "status" => 1]);
        return;
    } elseif ($newblog['outputStatus'] == 1200 && $newblog['output'] == false) {
        http_response_code(201);
        echo json_encode(["message" => "Blog was NOT created. Please try again.", "status" => 0]);
        return;
    } elseif ($newblog['outputStatus'] == 1400) {
        errorDiag($newblog['output']);
        return;
    } else {
        http_response_code(400);
        echo json_encode(["message" => $newblog['output'], "status" => 2]);
        return;
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Unable to create blog. Fill all fields.", "status" => 3]);
    return;
}
