
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// No unathorized access allowed
session_start();
if (!isset( $_SESSION['adminName'] ) ) { header('location:/admin/login.php'); }
// show a little class will ya?
include('class_lib.php');

$customers  = new Customer(); 
$campaigns  = new Campaign();
$videos     = new Video();
$admin      = new Admin($_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );
?>

<div class="adminSession">
<?php echo $admin->name. " [" . $admin->role . "] Last: ". $admin->lastOn; ?>  <a href="logout.php">Logout</a>
</div>

<div class="container">

<h3>Dashboard</h3>

<pre>
<?php
foreach($customers->customers($admin) as $cust){

    echo strtoupper($cust->customer_name . " [" .$cust->adminName) ."]\n\n";
    
        foreach( $campaigns->campaigns( $cust->customer_id ) as $camp){

            echo $camp->campaign_name ."(" . date('Y-m-d',strtotime($camp->created)) .") ";
            echo $camp->status == 1 ? "[Active]" : "[Inactive]" ;
            echo " Assigned Pi Client: " . $camp->PI_UID ;
            echo "\n";

            foreach($videos->videos( $camp->campaign_id ) as $idx => $video ){

                echo "\t" . ($idx+1) . ". ". $video->video_title . ": " . $video->youtube_id . "\n";
            }

            echo "\n";

        }

}


?>
</pre>
</div>
