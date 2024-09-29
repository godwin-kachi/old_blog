<?php

$pilotapiware = "pilotapi";


if($coreUrl == "getone" && $_SERVER['REQUEST_METHOD'] == 'POST'){

        include "./$pilotapiware/getuser.php";

}
elseif($coreUrl == "getonebyemail" && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$pilotapiware/getuserbyemail.php";

}
elseif($coreUrl == "getall"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$pilotapiware/getusers.php";

}
elseif($coreUrl == "createone"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$pilotapiware/createuser.php";

}
elseif($coreUrl == "createone_wp"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$pilotapiware/createuser_wp.php";

}
elseif($coreUrl == "updateone" && ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH')){

    // Update own updatable info (firstname, lastname and password)
    include "./$pilotapiware/updateuser.php";

}
elseif($coreUrl == "deleteone" && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    include "./$pilotapiware/deleteuser.php";

}elseif($coreUrl == "search" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/searchuser.php";
}
elseif($coreUrl == "authcheck" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/authcheck.php";
}
elseif($coreUrl == "userlogin" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/userlogin.php";
}
elseif($coreUrl == "userlogout" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/userlogout.php";
}
elseif($coreUrl == "passchange"  && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Users to change thier own password passwordlessly
    include "./$pilotapiware/changepassword.php";
}
elseif($coreUrl == "passchangeother"  && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Admins users to change thier the password of any user passwordlessly
    include "./$pilotapiware/changepassword2.php";
}
elseif($coreUrl == "verifyemail"   && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/verifyuser.php";
}
elseif($coreUrl == "resetpass"   && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$pilotapiware/resetpassword.php";
}
else{
    http_response_code(200);
    echo json_encode(["reqUrl" => "Requested URL doesnot exist", "resbc"=>false]);
    return;
}

