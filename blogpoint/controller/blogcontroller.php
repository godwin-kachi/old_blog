<?php
$blogapiware = "blogapi";


if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getone"){

        include "./$blogapiware/getblog.php";

}
else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getuserall"){

    include "./$blogapiware/getuserblogs.php";

}
else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getall"){

    include "./$blogapiware/getblogs.php";

}
else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/createone"){

    include "./$blogapiware/createblog.php";

}
else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/updateone"){

    include "./$blogapiware/updateblog.php";

}
else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/deleteone"){

    include "./$blogapiware/deleteblogs.php";

}else if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/search"){
    
    include "./$blogapiware/searchblog.php";
}
else{
    http_response_code(200);
    echo json_encode(["reqUrl" => "Requested URL doesnot exist", "resbc"=>false]);
    return;
}

