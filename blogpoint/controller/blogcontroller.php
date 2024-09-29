<?php
$apiware = "blogapi";

if($uaction == "getone" && $_SERVER['REQUEST_METHOD'] == 'GET'){

        include "./$apiware/getblog.php";

}
else if($uaction == "getuserall"  && $_SERVER['REQUEST_METHOD'] == 'GET'){

    include "./$apiware/getuserblogs.php";

}
else if($uaction == "getall"  && $_SERVER['REQUEST_METHOD'] == 'GET'){

    include "./$apiware/getblogs.php";

}
else if($uaction == "createone"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$apiware/createblog.php";

}
else if($uaction == "updateone"  && ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH')){

    include "./$apiware/updateblog.php";

}
else if($uaction == "deleteone" && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    include "./$apiware/deleteblogs.php";

}else if($uaction == "search" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$apiware/searchblog.php";
}
else{
    http_response_code(200);
    echo json_encode(["reqUrl" => "Requested URL doesnot exist", "resbc"=>false]);
    return;
}

