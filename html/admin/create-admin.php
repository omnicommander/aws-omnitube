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
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <h2>Create Administrator</h2>
        <form method="POST" action="createadmin.php" >

                    <div>
                        <label for="adminName">Name</label>
                        <input type="text" name="adminName" id="adminName">
                    </div>

                    <div>
                        <label for="email">Email address</label>
                        <input type="mail"  placeholder="Email" name="email" id=email>
                   </div>
                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="pass" id="password">
                    </div>
                    <div>
                        <label for="role">Role</label>
                        <select name="role" id="role">
                        <option value="1">Admin</option>
                        <option value="2">Campaign Manager</option>
                        </select>
                    </div>
                    <div>
                        <input type="submit" value="GO">
                    </div>
        </form> 

        <script src="js/index.js" async defer></script>

    </body>
</html>