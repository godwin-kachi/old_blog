<?php
$xPilot = 0;
$yPilot = 1;
$zPilot = 10;


function cleanData($data){
    return htmlspecialchars(strip_tags(trim($data)));
}

function scrubUcode($ucode){
    return $ucode->user_code;
}

function authGateCheck($udata) {
    if (!isset($udata->blogserv_lsc) || empty($udata->blogserv_lsc) || $udata->blogserv_lsc == null || trim($udata->blogserv_lsc) == '' || !isset($udata->blogserv_ssc) || empty($udata->blogserv_ssc) || $udata->blogserv_ssc == null || trim($udata->blogserv_ssc) == '' || !isset($udata->blogserv_usc) || empty($udata->blogserv_usc) || $udata->blogserv_usc == null || trim($udata->blogserv_usc) == '') {

        return false;
    }
    return true;
}


function authGateCheck2($dbdata, $supplied_data){
    if ($dbdata['user_code'] != scrubUserCode($supplied_data)) {
      return false;
    }
    return true;
}

function scrubUserCode($ucode){
    return substr($ucode->blogserv_usc, 108);
}

function scrubUserCode2($ucode){
    return intval(substr($ucode->blogserv_usc, 126, strlen($ucode->blogserv_usc)-144));
}

function prepareUserCode($ucode){
    return substr(md5(time()), 0, 18) . substr(md5(time()), 0, 18) . substr(md5(time()), 0, 18) . substr(md5(time()), 0, 18) . substr(md5(time()), 0, 18) . substr(md5(time()), 0, 18) . $ucode;
}

function prepareUserCode2(){
    return hash('sha512',microtime()) ;
}

function prepareUserCode3(){
    return hash('sha256',microtime()) ;
}

function isPilot3($uData){
    return $uData['role_id'] == 10 ? true : false; 
}

function isPilot2($uData){
    return $uData['role_id'] == 1 ? true : false; 
}

function isPilot1($uData){
    return $uData['role_id'] == 0 ? true : false; 
}


function errorDiag($err){
    if (stripos($err, 'duplicate')) {
        return ["message" => "FAILED: Entity already exists.", "status" => 50];
    }
    elseif (stripos($err, 'Invalid parameter')) {
        return ["message" => "Internal query parameter error.", "status" => 51];
    }
    elseif (stripos($err, 'column') || stripos($err, 'unknown')) {
        return["message" => "Internal or external: A column name is wrong.", "status" => 52];
    }
    elseif (stripos($err, 'SQL syntax')) {
        return ["message" => "Internal : Issue with SQL query syntax.", "status" => 53];
    }
    elseif (stripos($err, 'base table')) {
        return ["message" => "Internal : Table name does not exist.", "status" => 54];
    }
    else{
        return ["message" =>"Error", "error"=>$err, "status" => 55];
    }

}