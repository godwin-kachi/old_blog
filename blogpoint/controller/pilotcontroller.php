<?php

$pilotapiware = "pilotapi";


if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getone" && $_SERVER['REQUEST_METHOD'] == 'POST'){

        include "./$pilotapiware/getuser.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getonebyemail" && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$pilotapiware/getuserbyemail.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getall"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$pilotapiware/getusers.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/createone"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$pilotapiware/createuser.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/createone_wp"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$pilotapiware/createuser_wp.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/updateone" && ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH')){

    // Update own updatable info (firstname, lastname and password)
    include "./$pilotapiware/updateuser.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/deleteone" && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    include "./$pilotapiware/deleteuser.php";

}elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/search" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/searchuser.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/authcheck" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/authcheck.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/userlogin" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/userlogin.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/userlogout" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/userlogout.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/passchange"  && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Users to change thier own password passwordlessly
    include "./$pilotapiware/changepassword.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/passchangeother"  && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Admins users to change thier the password of any user passwordlessly
    include "./$pilotapiware/changepassword2.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/verifyemail"   && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/verifyuser.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/resetpass"   && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/resetpassword.php";
}
else{
    http_response_code(200);
    echo json_encode(["reqUrl" => "Requested URL doesnot exist", "resbc"=>false]);
    return;
}

