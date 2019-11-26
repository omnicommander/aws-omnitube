<?php
session_start();
if ( isset( $_SESSION['adminName'] ) ) {
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages
   header('location:/admin/dashboard.php')  ;
} 

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
        <link rel="stylesheet" href="../css/style.css">
        <script src="https://code.jquery.com/jquery-1.7.min.js"></script>
    </head>
    <body>
    <nav><a href=/>Home</a></nav>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <h2>Administration login</h2>
        <div id="notify"></div>
        <form method="POST" action="login.php" >
                    <div>
                        <label for="email">Email address</label>
                        <input type="mail"  placeholder="Email" name="email" id=email>
                   </div>
                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="pass" id="password">
                    </div>
                    <div>
                        <input type="submit" value="GO">
                    </div>
        </form> 

        <script src="js/admin.js" async defer></script>

    </body>
</html>