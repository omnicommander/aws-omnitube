<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// No unathorized access allowed
session_start();
if (!isset( $_SESSION['adminName'] ) ) { header('location:/admin/login.php'); }
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>OmniTube Service</title>
        <meta name="description" content="The Service to the OmniTube Clients">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/admin.css">
        <script src="https://code.jquery.com/jquery-1.7.min.js"></script>
    </head>
    <body>
<?php
// show a little class will ya?
include('lib/class_lib.php');
$customers  = new Customer(); 
$campaigns  = new Campaign();
$videos     = new Video();
$admin      = new Admin($_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );
$menu       = new menu();

?>
<div class="adminSession">
    <span class="session"><?php  echo $admin->name. " Last On: ". $admin->lastOn; ?></span>
    <?php echo $menu->dashboard(); ?>  
</div>


<div class="content">

<h3>Dashboard</h3>
<h4>Customers</h4>
<!-- <pre> -->
<?php

foreach($customers->fetchAllCustomers($admin) as $cust){
    echo '<div class="custLink"><a href="customer.php?id='.$cust->customer_id.'">' .  strtoupper( $cust->customer_name ). "</a></div>";

}


?>


</div>

<footer class="footer">
     &copy; Omnicommander <?php echo date('Y'); ?>
</footer>

</body>
</html>