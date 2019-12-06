<?php
// New administrator or manager account
// OmniTube Service API 
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require('../lib/class_lib.php');

$admin = new Admin();
extract($_POST);

 $admin->newAdmin($adminName, $email, $pass, $role);
 exit;
