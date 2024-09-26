<?php
include '../config/autoloader.php';

// required headers
header("Access-Control-Allow-Origin:" . $configx["dbconnx"]["ORIGIN"]);
header("Content-Type:" . $configx["dbconnx"]["CONTENT_TYPE"]);
header("Access-Control-Allow-Methods:" . $configx["dbconnx"]["POST_METHOD"]);
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

$user = new User($conn);

$user->user_id = 5;

// $regenStatus = $user->reGenerateUserCode();

// $res = $regenStatus['output']->fetch(PDO::FETCH_ASSOC);
// Check affected rows

// http_response_code(500);
// echo json_encode(["message" => $regenStatus, "status" => 7]);
// return; 


// Get a Use
// $the_user = $user->getAllUsers();
$usdata['role_id'] = 10;

if(!isCatapillar($usdata) && !isPilot($usdata)){
    http_response_code(500);
    echo json_encode(["message" => false, "status" => 7]);
    return; 
}
else{
    http_response_code(500);
    echo json_encode(["message" => true, "status" => 8]);
    return; }


// Logout
// $result = $user->userLogout();

// $res = substr(sha1(time()), 0);
// $ressha1 = substr(sha1(time()), 0);
// $res = hash('sha256', time().uniqid());
// $res2 = hash('sha256', time().uniqid());

// $res = hash('sha512', microtime());
// $res2 = hash('sha256', microtime());

// $res = microtime();
// $res2 = microtime();
// http_response_code(500);
// echo json_encode(["res"=>$res,"length"=>strlen($res), "status" => 7]);
// return;

// $resmd2 = substr(sha1(time()), 0);
// $resmd4 = substr(sha1(time()), 0);
// $res2 = substr(sha1(time()), 0);
// $res2 = substr(sha1(time()), 0);

$c1 = substr(sha1(time()), 0,5);
$c2 = substr(sha1(time()), 0,10);
$c3 = substr(sha1(time()), 0,15);
$qid = random_int(1,30);
$qofs = random_int(1,9);
$qid2 = $qid+$qofs;
$qcode = $c1 . $qofs . $c2 . $qid2 . $c3;

$prep_qcode = "armw3k4kdk" . $qcode;
$scr_qcode = substr($prep_qcode, 10);

$of_scr_qcode = substr($prep_qcode, 10+5, 1);
$id_scr_qcode = substr($prep_qcode, 10+5+1+10, strlen($qid2))-$of_scr_qcode;

$of_scr_qcode_2 = $scr_qcode[0];
$id_scr_qcode_2 = substr($scr_qcode, 10, strlen($qid2));


// pos
// 
http_response_code(500);
echo json_encode(["qid"=>$qid,"length"=>strlen($qid),"qofs"=>$qofs,"length2"=>strlen($qofs),"qcode"=>$qcode,"length3"=>strlen($qcode),"prep_qcode"=>$prep_qcode,"length4"=>strlen($prep_qcode),"scr_qcode"=>$scr_qcode,"length5"=>strlen($scr_qcode),"of_scr_qcode"=>$of_scr_qcode,"length6"=>strlen($of_scr_qcode),"id_scr_qcode"=>$id_scr_qcode,"length7"=>strlen($id_scr_qcode), "status" => 7]);
return;

// md5(32) sha1(40)

$id = 3;
$ucode = uc_generateUserCode($id);
$pucode['globetk_usc'] = uc_prepareUserCode($ucode);
$scrub_pucode = uc_scrubUserCode($pucode);
$isMatched = $scrub_pucode == $ucode ? true : false;

function uc_generateUserCode($uid){
    $offset = random_int(1,9);
    $usid = $uid + $offset;
    $fullcode = $offset . substr(md5(time()), 0) . "000" . $usid . "000" . substr(sha1(time()), 0);
    return [$fullcode, strlen($usid)];
}
function uc_scrubUserCode($ucode){
    return substr($ucode['globetk_usc'], 128);
}

function id_scrubUserCode2($ucode){
    return intval(substr($ucode->globetk_usc, 126, strlen($ucode->globetk_usc)-144));
}

function uc_prepareUserCode($ucode){
    return hash('sha512',microtime()) . $ucode;

}

http_response_code(500);
echo json_encode(["id"=>$id,"id_length"=>strlen($id),"usid"=>$usid,"usid_length"=>strlen($usid),"offset"=>$offset,"offset_length"=>strlen($offset),"ucode"=>$ucode,"length"=>strlen($ucode),"pucode"=>$pucode,"length2"=>strlen($pucode['globetk_usc']),"scrub_pucode"=>$scrub_pucode,"scrub_pucode_length"=>strlen($scrub_pucode),"matched"=>$isMatched, "status" => 7]);
return;


// http_response_code(500);
// echo json_encode(["res"=>isCatapillar(10), "status" => 7]);
// return;

// $resCount = count(str_split($result));


// $id_count = strlen(strval($user->user_id));

// $keyid = intval(substr($user->user_code, 18, strlen($user->user_code)-36));

// Test genUserCode
// if($user->reGenerateUserCodeById()){
//     $usc = prepareUserCode($user->user_code);
// $keyid = scrubUserCode2($usc);
// $uscode = scrubUserCode($usc);

// http_response_code(500);
// echo json_encode(["o_usc"=>$user->user_code, "o_usc_count"=>strlen($user->user_code), "usc"=>$usc,"usc_count"=>strlen($usc), "keyid"=>$keyid, "uscode"=>$uscode,"uscode_count"=>strlen($uscode), "status" => 7]);
// return; 

// }else{
//     http_response_code(500);
// echo json_encode(["msg"=>"UserCode generation fialed", "status" => 0]);
// return;
// }

