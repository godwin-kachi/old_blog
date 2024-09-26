<?php

include './config/autoloader.php';

// required headers
header("Access-Control-Allow-Origin:" . $configx["dbconnx"]["ORIGIN"]);
header("Content-Type:" . $configx["dbconnx"]["CONTENT_TYPE"]);
header("Access-Control-Max-Age:" . $configx["dbconnx"]["MAX_AGE"]);
header("Access-Control-Allow-Headers:" . $configx["dbconnx"]["ALLOWED_HEADERS"]);

$reqUrlArr = explode("/", $reqUrl);

$udomain = $reqUrlArr[0];
$uapibox = $reqUrlArr[1] ?? NULL;
$uresource = $reqUrlArr[2] ?? NULL;
$uaction = $reqUrlArr[3] ?? NULL;
$param_id = $reqUrlArr[4] ?? NULL;

$coreUrl = "$udomain/$uresource/$uaction";

// http_response_code(200);
// echo json_encode(["reqUrl" => $reqUrlArr,"coreUrl" => $coreUrl,"udomain"=>$udomain,"upibox"=>$uapibox, "uresource"=>$uresource,"uaction"=>$uaction,"param_id"=>$param_id, "res"=>false]);
// return;

// if($coreUrl == "$udomain/" ){
if($uresource == "user"){
    include "./controller/usercontroller.php";
}
if($uresource == "pilot"){
    include "./controller/pilotcontroller.php";
}
else if($uresource == "blog"){
    include "./controller/blogcontroller.php";
}
else if($uresource == "comment"){
    include "./controller/commentcontroller.php";
}
else{
    http_response_code(200);
    echo json_encode(["reqUrl" => "Requested URL doesnot exist", "res1"=>false]);
    return;
}
