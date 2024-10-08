<?php
// include 'config.ini';
$configx = parse_ini_file('configuration.ini', true);

include 'database.php';
include 'authgate.php';


// Models
include '../models/User.php';
include '../models/Pilot.php';
include '../models/Blog.php';
include '../models/Comment.php';



// include '../models/Mail.php';


