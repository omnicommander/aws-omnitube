<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ------------------------------------------
// Jarvis -- The mind behind the service
// Classy php like none other, hand crafted
// class by a master developer with years of 
// experience. -- sfleming
// ------------------------------------------
include('lib/class_lib.php');

$videos     = new Video();

// echo json_encode($_POST);
if($_POST['action'] == 'updateVideo'){
    echo json_encode( $videos->updateVideo( $_POST ));
}

if($_POST['action'] == 'insertVideo'){
    echo json_encode( $videos->insertVideo( $_POST ));
}

if($_POST['action'] == 'deleteVideo' ){
    echo json_encode( $videos->deleteVideo( $_POST ));
}