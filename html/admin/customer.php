<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>TubeCommander v1</title>
        <meta name="description" content="The Service to the OmniTube Clients">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/admin.css">
        <script src="https://kit.fontawesome.com/fd442e054f.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-1.7.min.js"></script>
    </head>
    <body>
<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
// No unathorized access allowed
session_start();
if (!isset( $_SESSION['adminName'] ) ) { header('location:/admin/login.php'); }
// show a little class will ya?
include('lib/class_lib.php');


$customers  = new Customer(); 
$campaigns  = new Campaign();
$videos     = new Video();
$admin      = new Admin($_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );
$menu       = new menu();

if( isset($_GET['id']) ){
    $cid=$_GET['id'];
    $customer = $customers->fetchCustomer($cid);
    $campaigns = $customers->fetchCustomerCampaigns($cid);
    $customerArray = json_decode(json_encode($customer), true);
    $campaignArray = json_decode( json_encode($campaigns), true);
}

?>

<div class="adminSession"><?php  echo $admin->name. " Last On: ". $admin->lastOn . $menu->dashboard(); ?></div>
    
<div class="container">
        
    <div class="table-container" role="table" aria-label="Customers">
        <div class="flex-table header" role="rowgroup">
            <div class="flex-row first" role="columnheader">Customer</div>
            <div class="flex-row" role="columnheader">Customer Email</div>
            <div class="flex-row" role="columnheader">Customer Phone</div>
            <div class="flex-row" role="columnheader">Customer Website</div>
        </div>

        <div class="flex-table row" role="rowgroup">
            <?php foreach($customerArray[0] as $k => $v){ echo "<div class='flex-row' id=$k>$v</div>"; } ?>
        </div>
    </div>    
    <!-- Campaigns of this customer  -->
    <div class="table-container" role="table" aria-label="Campaigns">
        <div class="flex-table header " role="rowgroup">
            <div class="flex-row campaignHead" role="columnheader">Campaign</div>
            <div class="flex-row campaignHead" role="columnheader">Created</div>
            <div class="flex-row campaignHead" role="columnheader">Managed By</div>
            <div class="flex-row campaignHead" role="columnheader">Status</div>
        </div>
        <?php 
        foreach($campaignArray as $campaign){
            echo '<div class="flex-table row" role="rowgroup">';
            // grab campaignId remove from array
            $campaignId=array_shift($campaign);

            foreach($campaign as $key => $val){
                echo "<div class='flex-row campaign-content' id=$key>$val</div>";
            }
           
            echo "</div>";
            // List videos 
            ?>
            <div class="flex-table header" role="rowgroup">
            <div class="flex-row videoHead" role="columnheader">Video</div>
            <div class="flex-row videoHead" role="columnheader">YouTube ID</div>
            <div class="flex-row videoHead" role="columnheader">Added</div>
            
        </div>
        <?php

            $videos = json_decode(json_encode($customers->fetchCustomerCampaignVideos($campaignId)), true);
                        
            foreach($videos as $video){
                echo '<div class="flex-table header " role="rowgroup">';
                $videoId=array_shift($video);
                    foreach($video as $vKey => $vVal){
                        echo "<div class='flex-row video-content' id=$videoId>$vVal</div>";
                    }
                    echo "</div>";
            }
            echo "</div>";
        }
        ?>
       </div>
    </div>


<?php 
    
?>