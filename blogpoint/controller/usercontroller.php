<?php

$apiware = "userapi";



if($uaction == "getone" && $_SERVER['REQUEST_METHOD'] == 'POST'){

        include "./$apiware/getuser.php";

}
elseif($uaction == "getonebyemail" && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$apiware/getuserbyemail.php";

}
elseif($uaction == "getall"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$apiware/getusers.php";

}
elseif($uaction == "createone"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$apiware/createuser.php";

}
elseif($uaction == "createone_wp"  && $_SERVER['REQUEST_METHOD'] == 'POST'){

    include "./$apiware/createuser_wp.php";

}
elseif($uaction == "updateone" && ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH')){

    // Update own updatable info (firstname, lastname and password)
    include "./$apiware/updateuser.php";

}
elseif($uaction == "updateother" && ($_SERVER['REQUEST_METHOD'] == 'PUT' || $_SERVER['REQUEST_METHOD'] == 'PATCH')){

    // Update other user data (ftirstname, lastname, password, role_id, active)
    include "./$apiware/updateuser_v2.php";

}
elseif($uaction == "deleteone" && $_SERVER['REQUEST_METHOD'] == 'DELETE'){

    include "./$apiware/deleteuser.php";

}elseif($uaction == "search" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$apiware/searchuser.php";
}
elseif($uaction == "authcheck" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$apiware/authcheck.php";
}
elseif($uaction == "login" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$apiware/userlogin.php";
}
elseif($uaction == "logout" && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$apiware/userlogout.php";
}
elseif($uaction == "passchange"  && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Users to change thier own password passwordlessly
    include "./$apiware/changepassword.php";
}
elseif($uaction == "passchangeother"  && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Admins users to change thier the password of any user passwordlessly
    include "./$apiware/changepassword2.php";
}
elseif($uaction == "verifyemail"   && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$apiware/verifyuser.php";
}
elseif($uaction == "resetuserpass"   && $_SERVER['REQUEST_METHOD'] == 'POST'){
    
    include "./$apiware/resetpassword.php";
}
else{
    http_response_code(200);
    echo json_encode(["reqUrl" => "Requested URL doesnot exist", "resbc"=>false]);
    return;
}

