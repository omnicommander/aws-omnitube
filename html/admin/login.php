
<?php
// OmniTube Service API 
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require('lib/class_lib.php');
$admin = new Admin();
extract($_POST);

if($email && $pass) { $admin->login($email, $pass); }
exit;
