<?php 
// ========== Administrators Control Panel Page ==========

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset( $_SESSION['adminName'] ) ) { header('location:/admin'); }

include('lib/class_lib.php');
$admin      = new Admin($_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );
$menu       = new menu();
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>TubeCommander Adminstration Control Panel</title>
        <meta name="description" content="TubeCommander SaaS">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/admin.css">
        <script src="https://kit.fontawesome.com/fd442e054f.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
    </head>
    <body>
    <div class="adminSession"><?php  echo $admin->name. " Last On: ". $admin->lastOn . $menu->panel(); ?></div> 
    <div class="container content">
    <header class="dashboard"><h2>Administration Control Panel</h2></header>
    <div class="panel" style="border:1px solid purple;margin: 10px 0;padding:40px;">
        <h4>Nothing to see here, move along while this section is being built.</h4> 
        <?php
        if( $admin->role !='Admin' ){ 
            echo 'You dont have permission, sorry. Now kindly go away.';
            print_r($_SESSION);
            exit;
        }

        echo "<pre>";
        print_r($admin->list());
        echo "</pre>";

        ?>
        </div>

</div><!-- content --> 


<!-- FOOTER -->
<?php echo $admin->footer(); ?>

</body>
</html>