<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
// No unathorized access allowed
session_start();
if (!isset( $_SESSION['adminName']) || (!isset($_GET['id'])) ) { header('location:/admin/'); }
// show a little class will ya?
include('lib/class_lib.php');
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>TubeCommander Customer</title>
        <meta name="description" content="TubeCommander SaaS">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/admin.css">
        <script src="https://kit.fontawesome.com/fd442e054f.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
    </head>
    <body>
<?php 
$customers  = new Customer(); 
$campaigns  = new Campaign();
$videos     = new Video();
$admin      = new Admin($_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );
$menu       = new menu();
$aid        = $admin->admin_id; 
$cid        = $_GET['id'];
$customer   = $customers->fetchCustomer($cid);
$campaigns  = $customers->fetchCustomerCampaigns($cid);
$customerArray = json_decode(json_encode($customer), true);
$campaignArray = json_decode( json_encode($campaigns), true);

?>

<div class="adminSession"><?php  echo $admin->name. " Last On: ". $admin->lastOn . $menu->dashboard(); ?></div> 
<div class="container content">
    <header class="dashboard"><h3>Customer Dashboard</h3></header>
    <div class="table-container" role="table" aria-label="Customers">
        <div class="flex-table header" role="rowgroup">
            <div class="flex-row first" role="columnheader">Customer</div>
            <div class="flex-row" role="columnheader">Customer Email</div>
            <div class="flex-row" role="columnheader">Customer Phone</div>
            <div class="flex-row" role="columnheader">Customer Website</div>
            <div class="flex-row" role="columnheader">Create Date</div>
            <div class="flex-row" role="columnheader">Created By</div>
        </div>
        <div class="flex-table row" role="rowgroup">
            <?php foreach($customerArray[0] as $k => $v){ 
            //  if customer_contact_email make it a link
                switch($k){
                    case 'customer_contact_email':
                        echo "<div class='flex-row' id=$k><a href='mailto:$v'>$v</a></div>";         
                    break;
                     default:
                     echo "<div class='flex-row' id=$k>$v</div>"; 

                }
                
                
                } ?>
        </div>
    </div>    

    <!-- Campaigns of this customer  -->

    <div class="table-container" role="table" aria-label="Campaigns">
        
    <?php  foreach($campaignArray as $campaign){ ?>
            
            <div class="flex-table header" role="rowgroup">
                <div class="flex-row campaignHead" role="columnheader">Pi Client</div>
                <div class="flex-row campaignHead" role="columnheader">Pi ID</div>
                <div class="flex-row campaignHead" role="columnheader">Created</div>
                <div class="flex-row campaignHead" role="columnheader">Managed By</div>
                <div class="flex-row campaignHead" role="columnheader">Status</div>
            </div>

    <?php
                        
            // grab campaignId and remove from array
            $campaignId=array_shift($campaign);
            echo "<div class='flex-table row campaign-table' role='rowgroup' data-campaign-id='$campaignId'>";

            foreach($campaign as $key => $val){
                echo "<div class='flex-row campaign-content $key'>$val</div>";
            }
           
            echo "</div><!-- campaign-table -->";

            // List videos in the campaign
            ?>
            <div class="flex-table header" role="rowgroup">
            <div class="flex-row videoHead" role="columnheader">Title</div>
            <div class="flex-row videoHead" role="columnheader">YouTube ID</div>
            <div class="flex-row videoHead" role="columnheader">Date Added</div>
            <div class="flex-row videoHead" role="columnheader">Action</div>
        </div>
        <?php

            $videos = json_decode(json_encode($customers->fetchCustomerCampaignVideos($campaignId)), true);
                        
            foreach($videos as $video){
                
                $videoId = array_shift($video);
                
                echo "<div class='flex-table header ' data-video-id='$videoId' data-campaign-id='$campaignId' data-customer-id='$cid' role='rowgroup'>";
                
                    foreach($video as $vKey => $vVal){
                        echo "<div class='flex-row video-content' id='$vKey'>$vVal";
                        echo $vKey == 'youtube_id' ? " <span data-youtube-id='$vVal' class='vLink'></class>": "";
                        echo "</div>";
                    }
                    // last column of the row
                    echo "<div class='flex-row video-content'>";
                    echo "<span class='vEdit' data-video-id='$videoId' >Edit</span>  <span data-video-id='$videoId' class='vDelete'>Delete</span> ";
                    echo "</div>";

                echo "</div><!-- flex-table header -->";
            }
            
            echo "<div class='flex-table header flex-row video-content newVideo' data-campaign-id='$campaignId'>Add Video</div>";
            
        ?>
           
        <?php    
       
    }
        // Add Campaign LINK
        echo '<div class="table-container" role="table">';
        echo '<div class="flex-row newCampaign" data-admin_id="'.$aid.'" data-customer_id="'.$cid.'">Add Client</div>';
        echo '</div><!-- table-container-->';
    
    echo "</div>";
?>
</div><!-- container content -->

<script src="js/customer.js"></script>


<!-- DIALOGS BEGIN HERE MAKE --  NO EDITS BELOW THIS LINE!!! -->


<!-- New Video Modal dialog HTML-->
<div id="newVideo" title="Add New Video" hidden="hidden">
    <div class="inputContainer">Video Title <input id="video_title" type="text" required></div>
    <div class="inputContainer">Youtube ID <input id="youtube_id" type="text" required></div>
    <div id="dataContainer"></div>
</div>

<!-- Edit Video Row Dialog HTML-->

<div id="vEdit" title="" hidden="hidden" >   
    <div class="inputContainer">Video Title <input id="video_title" type="text" value=""></div>
    <div class="inputContainer"> Youtube ID <input id="youtube_id" type="text" value=""> </div>  
    <div id="dataContainer"></div>
</div>

<!-- Delete Video Dialog HTML -->
<div id="vDelete" title="" hidden="hidden">
    <div class='caution'>Caution! There is no Un-do here!</div>
    <div id="dataContainer">
        <div class='vTitle'></div>
        <div class='youtube_id'></div>
    </div>
</div>

<!-- Video View Dialog HTML -->
<div id="vLink" title="" hidden="hidden">
    <div id="dataContainer">    
    <iframe width="700" height="480" src="https://www.youtube.com/embed/n9kfdEyV3RQ?rel=0&amp;controls=0&amp;showinfo=0&amp;modestbranding=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</div>

<!-- New Campaign Dialog HTML -->
<div id="newCampaign" hidden="hidden">
    <div id="dataContainer">
        <div class="inputContainer"><input type="hidden" id="customer_id"></div>
        <div class="inputContainer"><input type="hidden" id="admin_id"></div>
        <div class="inputContainer">Pi Client Name (Friendly)<input type="text" id="campaign_name"> </div>
        <div class="inputContainer"> Pi Client ID <input type="text" id="client_id"> </div>
    </div>
</div>

<!-- PI statistical Dialog HTML -->
<div id="pistat" hidden="hidden">
    <div id="dataContainer">
        <div class='info'>
        
        </div>
    </div>
</div>



<!-- FOOTER -->
<?php echo $admin->footer(); ?>

</body>
</html>