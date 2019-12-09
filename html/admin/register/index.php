<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

// No unathorized access allowed
session_start();
if (!isset( $_SESSION['adminName'] ) ) { header('location:/admin/'); }
include('../lib/class_lib.php');
$menu = new menu();
$admin      = new Admin($_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );
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
        <link rel="stylesheet" href="../css/admin.css">
        
        <script src="https://code.jquery.com/jquery-1.7.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <div class="adminSession">
        <span class="session"><?php  echo $admin->name. " Last On: ". $admin->lastOn; ?></span>
        <?php echo $menu->dashboard(); ?>  
    </div>

<div class="container content">
        
    <header class="dashboard"><h2>Register New User</h2></header>

    <div class="form-container">
        <div id="notify"></div>
        <form id="registerForm" method="POST" action="createadmin.php" >

            <ul class="flex-outer">
                <li>
                    <label for="adminName">Name</label>
                    <input type="text" name="adminName" id="adminName" required>
                </li>

                <li>
                    <label for="email">Email address</label>
                    <input type="mail"  placeholder="Email" name="email" id=email required>
                </li>
                <li>
                    <label for="password">Password</label>
                    <input type="password" name="pass" id="password" required>
                </li>
                <li>
                    <label for="role">Role</label>
                    <select class="select-css" name="role" id="role" >
                    <option value="1">Admin</option>
                    <option value="2" selected >Manager</option>
                    </select>
                </li>
            <li>
                <button type="submit">REGISTER</button>
            </li>
            </ul>
        </form> 

    </div>  <!-- form-container -->

    
</div><!-- container -->
<!-- FOOTER -->
<?php echo $menu->footer(); ?>

<script src="../js/admin.js" async defer></script>

    </body>
</html>