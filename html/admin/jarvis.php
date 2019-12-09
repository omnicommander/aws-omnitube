<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
// ------------------------------------------
// Jarvis -- The mind behind the service
// Classy php like none other, hand crafted
// class by a master developer with years of 
// experience. -- sfleming
// ------------------------------------------
include('lib/class_lib.php');
$admin      = new Admin($_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );
$videos     = new Video();
$customer   = new Customer();
$client     = new Client();

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

if($_POST['action'] == 'updateCustomer'){
echo json_encode( $customer->updateCustomer( $_POST ) );
}

if($_POST['action'] == 'insertCustomer'){
echo json_encode( $customer->insertCustomer( $_POST ) );
}

if( $_POST['action'] == "insertCampaign" ){
    echo json_encode( $customer->insertCampaign( $_POST ) );
}

// client requests from Monitor
// =============================

if( $_POST['action'] == 'fetchClientMonitor' ){
    echo json_encode( $client->monitor( $_POST ) );
}


// Admin requests for panel
// ============================

if( $_POST['action'] == 'updateAdmin' ){
    echo json_encode( $admin->update( $_POST ) );
}