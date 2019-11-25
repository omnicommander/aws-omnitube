<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$config = parse_ini_file('/var/www/private/config.ini'); 

$mysqli = new mysqli($config['server'], $config['username'], $config['password'],$config['database']);
if ($mysqli->connect_errno) {
    echo "Sorry, this website is experiencing problems, and is unavailable to you currently. Why dont you go have a coffee, and hope it returns by the time you get back?";
    exit;
}