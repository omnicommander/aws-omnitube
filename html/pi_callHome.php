<?php
// Activated when pi curls with id 
// returns json array of youtube ids 
// which PI client downloads from youtube.com
// ==========================================
require('functions/db.php');
require('admin/lib/class_lib.php');

$client = new Client();
$ipaddr = getIpAddr();

// die if no get index
if(!$_GET['id']){ exit;}
 
$cId        = $_GET['id'];
$customer   = $client->getVideos($cId, $ipaddr);

// output pure, and absolutely wonderful json
header('Content-Type: application/json');
echo json_encode($customer, true);
        
// shut da door on your way out
mysqli_close($mysqli);

// and, we're outta here, lets go down to the lobby and get ourselves a treat!!!
exit;

// getIpAddr -- captures this machines requesting IP address

function getIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>