<?php
// fetchVideos -- requests made be PI clients arrive here.
// Requires id to be passed, and must be pre-registered
// in admin control to be activated. 
// =======================================================


require('functions/db.php');
require('functions/pi_functions.php');

// die if no id passed.
if(!$_GET['id']){ exit; }else{ $cId = $_GET['id'];}

$customer = pi_getVideos($cId);

// spew results
header('Content-Type: application/json');
echo json_encode($customer, true);
mysqli_close($mysqli);
exit;
?>