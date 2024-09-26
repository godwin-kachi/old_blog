<?php

$userapiware = "userapi";



if($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getone" && $_SERVER['REQUEST_METHOD'] == 'POST'){

        include "./$userapiware/getuser.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getonebyemail" && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$userapiware/getuserbyemail.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/getall"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$userapiware/getusers.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/createone"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$userapiware/createuser.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/createone_wp"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$userapiware/createuser_wp.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/updateone" && ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH')){

    // Update own updatable info (firstname, lastname and password)
    include "./$userapiware/updateuser.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/updateother" && ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH')){

    // Update other user data (ftirstname, lastname, password, role_id, active)
    include "./$userapiware/updateuser_v2.php";

}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/deleteone" && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    include "./$userapiware/deleteuser.php";

}elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/search" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$userapiware/searchuser.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/authcheck" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$userapiware/authcheck.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/login" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$userapiware/userlogin.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/logout" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$userapiware/userlogout.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/passchange"  && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Users to change thier own password passwordlessly
    include "./$userapiware/changepassword.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/passchangeother"  && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Admins users to change thier the password of any user passwordlessly
    include "./$userapiware/changepassword2.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/verifyemail"   && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$userapiware/verifyuser.php";
}
elseif($coreUrl == $configx["dbconnx"]["DOMAIN"] . "/$uresource/resetuserpass"   && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$userapiware/resetpassword.php";
}
else{
    http_response_code(200);
    echo json_encode(["reqUrl" => "Requested URL doesnot exist", "resbc"=>false]);
    return;
}

