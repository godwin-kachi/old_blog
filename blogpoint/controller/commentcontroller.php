<?php
$apiware = "commentapi";


if($uaction == "getone"  && $_SERVER['REQUEST_METHOD'] == 'GET'){

        include "./$apiware/getcomment.php";

}
else if($uaction == "getall"  && $_SERVER['REQUEST_METHOD'] == 'GET'){

    include "./$apiware/getcomments.php";

}
else if($uaction == "createone" && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$apiware/createcomment.php";

}
else if($uaction == "updateone"  && ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH')){

    include "./$apiware/updatecomments.php";

}
else if($uaction == "deleteone"  && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    include "./$apiware/deletecomments.php";

}else if($uaction == "search"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$apiware/searchcomments.php";

}
else{
    http_response_code(200);
    echo json_encode(["reqUrl" => "Requested URL doesnot exist", "res"=>false]);
    return;
}

