<?php
// Activated when pi curls with id 
// returns json array of youtube ids 
// which PI client downloads from youtube.com
// ==========================================

require('functions/db.php');
require('functions/pi_functions.php');

// die if no get index
if(!$_GET['id']){ exit;}else{ $cId = $_GET['id'];}


$customer = pi_getVideos($cId);

// output pure, and absolutely wonderful json
header('Content-Type: application/json');
echo json_encode($customer, true);
        
// shut da door on your way out
mysqli_close($mysqli);
// and, we're outta here
exit;
?>