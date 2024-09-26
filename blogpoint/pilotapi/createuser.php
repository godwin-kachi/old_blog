<?php
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]['POST_METHOD']);

// initialize object
$db = new Database($configx);
$conn = $db->getConnection();

// Check connection
if ($conn == null) {
    http_response_code(500);
    echo json_encode(["message" => "Database connection failed.", "status" => 2]);
    return;
}

$pilot = new Pilot($conn);

// get posted data
$data = json_decode(file_get_contents("php://input"));


// ===== Auth Gate Check =========================

if (!authGateCheck($data)) {
    // set response code - 503 service unavailable
    http_response_code(401);
    echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 30]);
    return;
}

$pilot->user_id = cleanData(scrubUserCode2($data));

if (!is_numeric($pilot->user_id) || !isset($pilot->user_id) || is_null($pilot->user_id) || !authGateCheck($data)) {
     // set response code - 503 service unavailable
     http_response_code(401);
     echo json_encode(["message" => "User Not authentificated.","result" => false, "status" => 31]);
     return;
}

$pilot_stmt = $pilot->getUser();

if ($pilot_stmt['outputStatus'] == 1400) {
        
    // set response code - 503 service unavailable
    http_response_code(503);
    echo json_encode( errorDiag($pilot_stmt['output']) );
    return;        
}

$pilotData = $pilot_stmt['output']->fetch(PDO::FETCH_ASSOC);

 // If user does not exist
 if (!$pilotData || empty($pilotData) || !authGateCheck2($pilotData, $data)) {
    // set response code - 404 not found
    http_response_code(404);
    echo json_encode(["message" => "User Not Authenticated.", "status" => 0]);
    return;
}

// ===== Auth Gate Check ends here ===============


// ===== Authorisation Gate Check ===============

if($pilotData['role_id'] <= 1 && intval($pilotData['active']) != 1){
    // set response code - 403 not found
    http_response_code(403);
    echo json_encode(["message" => "User Not Authorized to perform this action.", "status" => 40]);
    return;
}

// ===== Authorisation Gate Check ends here ===============


// Check for valid user email
if (empty($data->email) || $data->email == null || cleanData($data->email) == "" || !filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
    // set response code - 403 forbidden
    http_response_code(403);
    echo json_encode(["message" => "Please provide your valid email", "status" => 3]);
    return;
}

// Check for valid user email
if (empty($data->firstname) || $data->firstname == null || cleanData($data->firstname) == "" || strlen($data->firstname) < 3) {
    // set response code - 403 forbidden
    http_response_code(403);
    echo json_encode(["message" => "Please provide your valid firstname", "status" => 4]);
    return;
}

// Check for valid user lastname
if (empty($data->lastname) || $data->lastname == null || trim(cleanData($data->lastname)) == "" || strlen($data->lastname) < 3) {
    // set response code - 403 forbidden
    http_response_code(403);
    echo json_encode(["message" => "Please provide your valid lastname", "status" => 5]);
    return;
}


// ==== create with password or passwordless
if (!isset($data->passwordless) || empty($data->passwordless) || $data->passwordless == false || is_null($data->passwordless) || $data->passwordless == "") {
    // Check for valid user email
    if (empty($data->password) || $data->password == null || cleanData($data->password) == "") {
        // set response code - 403 forbidden
        http_response_code(403);
        echo json_encode(["message" => "Please provide your valid password", "status" => 6]);
        return;
    }

    // Check for valid user email
    if (strlen($data->password) < 12) {
        // set response code - 403 forbidden
        http_response_code(403);
        echo json_encode(["message" => "Your password must at least 12 characters long", "status" => 7]);
        return;
    }

    // Check for valid user email
    if (strpbrk($data->password, "<>&")) {
        // set response code - 403 forbidden
        http_response_code(403);
        echo json_encode(["message" => "Your password can not conatin <, >, or &", "status" => 7]);
        return;
    }

}
else{
    // Generate user password =================
    $data->password = $pilot->genPass();
    $pilot->password = $data->password;
    // Generate user password =================
}

 // Check for valid user email
 if (strpbrk($data->firstname, "<>&") || strpbrk($data->lastname, "<>&") || strpbrk($data->email, "<>&")) {
    // set response code - 403 forbidden
    http_response_code(403);
    echo json_encode(["message" => "Your firstname, lastname, email or password can not conatin <, >, or &", "status" => 7]);
    return;
}

// Sanitize data
$data->firstname = cleanData($data->firstname);
$data->lastname = cleanData($data->lastname);
$data->email = cleanData($data->email);
$data->password = cleanData($data->password);


// make sure data is not empty
if ($data->firstname != "" && $data->email != "" && $data->lastname != "" && $data->password != "") {

    // Create a User in order to saerch the user table and set user email for search
    $user = new User($conn);
    $user->email = $data->email;

    // Check if the user email already exists
    $user_stmt = $user->getUserByEmail();

    if ($user_stmt['outputStatus'] == 1000) {
        
        $userExits = $user_stmt['output']->fetch(PDO::FETCH_ASSOC);

        if($userExits) {

            // set response code - 200 ok
            http_response_code(400);
            // tell the user
            echo json_encode(["message" => "email already exists. Please choose another one.", "status" => 18]);
            return;

        }


        // Check if pilot user email exists
        $pilot->email = $data->email;

        $pilot_stmt = $pilot->getUserByEmail();

        if ($pilot_stmt['outputStatus'] == 1000) {

            $pilotExits = $pilot_stmt['output']->fetch(PDO::FETCH_ASSOC);

            if ($pilotExits) {
                // set response code - 200 ok
                http_response_code(400);
                // tell the user
                echo json_encode(["message" => "email already exists. Please choose another one.", "status" => 0]);
                return;
            }


            // Sanitize & set user property values
            $pilot->firstname = $data->firstname;
            $pilot->lastname = $data->lastname;
            $pilot->email = $data->email;
            $pilot->password = $data->password;

            // create the user
            $new_pilot = $pilot->createUser();

            if ($new_pilot['outputStatus'] == 1000 || $new_pilot['output'] == true) {

                // Send new user password ro email
                $to = $data->email;
                $subject = 'New User Registration';
                $message = 'We are delighted to have you onboard our platfrom.' . "\r\n" . 'Your password is: ' . $data->password;
                $headers = 'From: noreply@example.com'. "\r\n" . 'Reply-To: noreply@example.com'. "\r\n" . 'X-Mailer: PHP/'. phpversion();

                $mailSent = false;

                // Only send mail if not on localhost
                $whitelist = ['localhost', '127.0.0.1', '::1'];
                if (!in_array($_SERVER['SERVER_NAME'], $whitelist)) {

                    $mailSent = mail($to, $subject, $message, $headers);
                  
                }

                // set response code - 201 created
                http_response_code(201);
                echo json_encode(["message" => "New Admin was created successfully and your password has been sent to your email.", "password"=> $data->password,"mailsent"=>$mailSent, "status" => 1]);
                return;
                
            }
            elseif ($new_pilot['outputStatus'] == 1200 || $new_pilot['output'] == false) {

                // set response code - 201 created
                http_response_code(201);
                echo json_encode(["message" => "New Admin creation FAILED.Try again.", "status" => 0]);
                return;
                
            }
            elseif ($new_pilot['outputStatus'] == 1400) {

                // set response code - 503 service unavailable
                http_response_code(503);
                echo json_encode( errorDiag($new_stmt['output']) );
                return;
            }
            else {
                // set response code - 200 ok
                http_response_code(400);
                echo json_encode(["message" => $new_pilot['output'], "status" => 3]);
                return;
            }



        }
        elseif ($pilot_stmt['outputStatus'] == 1200) {

            // set response code - 503 service unavailable
            http_response_code(503);
            echo json_encode( errorDiag($pilot_stmt['output']) );
            return;
        }
        else {
            // set response code - 400 bad request
            http_response_code(400);
            echo json_encode(["message" => "Network issues. Try again.", "status" => 4]);
            return;
        }



    }elseif ($user_stmt['outputStatus'] == 1200) {

         // set response code - 503 service unavailable
        http_response_code(503);
        echo json_encode( errorDiag($user_stmt['output']) );
        return;
    }
    else {
        // set response code - 200 ok
        http_response_code(400);
        echo json_encode(["message" => "Network issues. Try again.", "status" => 4]);
        return;
    }

} else {

    // set response code - 400 bad request
    http_response_code(400);
    echo json_encode(["message" => "Unable to create user. Fill all fields.", "status" => 5]);
    return;

}


