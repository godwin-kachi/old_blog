<?php
include '../config/autoloader.php';

// required headers
header("Access-Control-Allow-Origin:" . $configx["dbconnx"]["ORIGIN"]);
header("Content-Type:" . $configx["dbconnx"]["CONTENT_TYPE"]);
header("Access-Control-Max-Age:" . $configx["dbconnx"]["MAX_AGE"]);
header("Access-Control-Allow-Headers:" . $configx["dbconnx"]["ALLOWED_HEADERS"]);

header("Access-Control-Allow-Methods:" . $configx["dbconnx"]['POST_METHOD']);


if(!isset($_POST['submitbtn'])){
    // header("location: ../blogger/create_blog.php");
    header("location: ../../blogger/login.php");
    exit();
}


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
// $data = json_decode(file_get_contents("php://input"));




// ===== Auth Gate Check =========================
/*
// if (!authGateCheck($data)) {
if (!authGateCheck($data)) {
    http_response_code(200);
    echo json_encode(["message" => "User Not authenticated.", "result" => false, "status" => 30]);
    // header("location: ../../blogger/login.php");
    return;
}

// Session check
$user->user_id = cleanData(scrubUserCode2($data));
// http_response_code(200);
// echo json_encode(["message" => $user->user_id, "result" => false, "status" => 31]);
// return;

if (!is_numeric($user->user_id)) {
    http_response_code(200);
    echo json_encode(["message" => "User Not authenticated.", "result" => false, "status" => 31]);
    return;
}

$userStmt = $user->getUser();
$userData = $userStmt['output']->fetch(PDO::FETCH_ASSOC);

// http_response_code(200);
// echo json_encode(["message" => $userData, "result" => false, "status" => 32]);
// return;



if (!$userData) {
    http_response_code(200);
    echo json_encode(["message" => $userData, "result" => false, "status" => 33]);
    return;
}

// // ===== Auth Gate Check 2 =====================
// if (!authGateCheck2($userData, $data)) {
//     http_response_code(401);
//     echo json_encode(["message" => "User Not authenticated.", "result" => false, "status" => 34]);
//     return;
// } 
*/

// var_dump(__FILE__);
// return;
// Make sure data is not empty

// if (!empty($data->title) || !empty($data->content) || !empty($user->user_id) || !empty($data->image)) {
if (!empty($_POST['title']) || !empty($_POST['content']) || !empty($_POST['user_id']) || !empty($_FILES['image'])) {

  
    // Sanitize & set blog property values
    $blog->title = cleanData($_POST['title']);
    $blog->content = $_POST['content'];
    $blog->user_id = cleanData($_POST['user_id']);
    // $blog->image = cleanData($_POST['image']);
    $blog->tags = empty($_POST['tags']) ? NULL : cleanData($_POST['tags']);
    $blog->categories = empty($_POST['categories']) ? NULL : cleanData($_POST['categories']);

    // handle image
    $extArr = ["png", "jpg", "jpeg"];

    $uid = uniqid();
    $image_name = "default.png";

   // This is the beginning of the original text

    $fileExt = explode(".", $_FILES["image"]["name"]);
    $ext = strtolower(end($fileExt));

    if (in_array($ext, $extArr)) {
        $image_name = $uid . $_FILES["image"]["name"];
    }

    // $target_dir = "../../blogger/assets/img/uploads/$image_name";
    $target_dir = "/var/www/html/old_blog/blogger/assets/img/uploads/";
    $blog->image = $image_name;
        $target_file = $target_dir .  basename($image_name);
    

    // $moved = move_uploaded_file(realpath($_FILES["image"]["tmp_name"]), $target_dir);
    var_dump(basename($_FILES["image"]["tmp_name"]));
    // var_dump("$moved");

    if (is_uploaded_file($_FILES['image']['tmp_name'])) {

//         // Attempt to move the uploaded file to the destination directory

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded successfully.";
        } else {
            echo "Sorry, there was an error moving the file.";
        }
    } else {
        echo "No file was uploaded or there was an upload error.";
    }



    // return;

    // This is the end of the original text

    // /home/kachi/Pictures/Screenshots/


//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // Directory to upload images
//     $target_dir = "uploads/";
//     // $target_dir = "/var/www/html/img_upload_test/uploads/";

//     // Path to the uploaded file
//     $target_file = $target_dir . basename($_FILES["image"]["name"]);

//     // Check if file was uploaded without errors
//     if (is_uploaded_file($_FILES['image']['tmp_name'])) {
//         // Attempt to move the uploaded file to the destination directory
//         if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
//             echo "The file " . basename($_FILES["image"]["name"]) . " has been uploaded successfully.";
//         } else {
//             echo "Sorry, there was an error moving the file.";
//         }
//     } else {
//         echo "No file was uploaded or there was an upload error.";
//     }
// }




    // $fileExt = explode(".", $_FILES["image"]["name"]);
    // $ext = strtolower(end($fileExt));

    // if (in_array($ext, $extArr)) {
    //     $image_name = $uid . $_FILES["image"]["name"];
    // }

    // $target_dir = "/var/www/html/old_blog/blogger/assets/img/uploads/;
    // $blog->image = $image_name;

    // // Get the absolute path of the uploaded file's temporary location
    // $tmp_file_path = realpath($_FILES['image']["tmp_name"]);
    // var_dump($tmp_file_path);

    // // Move the file from the temporary directory to the target directory
    // // $moved = move_uploaded_file($tmp_file_path, $target_dir);
    // // var_dump($moved);

    // return;



    // Create the assignment
    $newblog = $blog->createBlog();
    // http_response_code(200);
    // echo json_encode(["message" => $newblog, "result" => false, "status" => 32]);
    // return;

    if ($newblog['outputStatus'] == 1000 && $newblog['output'] == true) {

        

        http_response_code(201);
        echo json_encode(["message" => "Blog was created successfully","moved"=>$moved, "status" => 1]);
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
