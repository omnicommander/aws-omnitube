
<?php 

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include('lib/class_lib.php');
$site = new Site();


?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>TubeCommander by OmniCommander</title>
        <meta name="description" content="TubeCommander -- A Service for OmniCommander">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://kit.fontawesome.com/fd442e054f.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-1.7.min.js"></script>
    </head>
    <body>
<nav> 
    <ul class="menu">
        <li class='admin'><a href="admin/">Login</a></li>
    </ul>
</nav>
<div class="main-container content">

    <h2>Welcome To TubeCommander</h2>

    <div id="img-container"><img src="img/Raspi-PGB001.png" alt="Raspberry Pi" ></div>
    
    <h2>A Powerful and insightful CRM for OmniTube</h2>
    
    
</div>

<!-- Footer -->
<?php echo $site->footer; ?>
</body>
</html>