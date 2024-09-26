<?php
$commentapiware = "commentapi";


if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getone"){

        include "./$commentapiware/getcomment.php";

}
else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getall"){

    include "./$commentapiware/getcomments.php";

}
else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/createone"){

    include "./$commentapiware/createcomment.php";

}
else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/updateone"){

    include "./$commentapiware/updatecomments.php";

}
else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/deleteone"){

    include "./$commentapiware/deletecomments.php";

}else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/search"){

    include "./$commentapiware/searchcomments.php";

}
else{
    http_response_code(200);
    echo json_encode(["reqUrl" => "Requested URL doesnot exist", "res"=>false]);
    return;
}

