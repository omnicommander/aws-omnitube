
<?php
// OmniTube Service API 
ini_set('display_errors', 1);
error_reporting(E_ALL);

require('../functions/db.php');
require('../functions/admin.php');

extract($_POST);
if($email && $pass) { AdminLogin($email, $pass);}
