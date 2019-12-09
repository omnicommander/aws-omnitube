<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if ( isset( $_SESSION['adminName'] ) ) {
   header('location:/admin/dashboard.php')  ;
} 
require 'lib/class_lib.php';
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>TubeCommander</title>
        <meta name="description" content="The Service to the OmniTube Clients">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/admin.css">
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
    </head>
    <body>

<div class="menuContainer"><ul class="menu"><li class="home"><a href="/">Home</a> </li></ul></div>

<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div class="main-container content">
    <header>TubeCommander Login</header>
        <div class="form-container">
            <div id="notify"></div>
            <form method="POST" action="login.php" id="adminLoginForm" >
                <ul class="flex-outer">
                <li>                        
                    <label for="email">Email address / Username</label>
                    <input type="mail"  placeholder="Email" name="email" id=email required>
                </li>
                <li>
                    <label for="password">Password</label>
                    <input type="password" name="pass" id="password" required>
                </li>
                <li>
                    <button type="submit">Login</button>
                </li>
                </ul>
            </form> 
        </div>
</div>  
  <script src="js/admin.js" async defer></script>
  <?php print '<footer class="footer"> &copy; Omnicommander '. date('Y'). ' v0.1</footer>'; ?>
 </body>
</html>