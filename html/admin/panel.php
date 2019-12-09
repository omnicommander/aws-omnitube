<?php 
// ========== Administrators Control Panel Page ==========

ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset( $_SESSION['adminName'] ) ) { header('location:/admin'); }

include('lib/class_lib.php');
$admin      = new Admin($_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );
$menu       = new menu();
            if( $admin->role !='SuperAdmin' ){ 
                echo 'You dont have permission, sorry.';
                print_r($_SESSION);
                exit;
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
        <title>Administrators & Managers</title>
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
    <header class="dashboard"><h2>Admininstration</h2></header>
    <div class="panel">
        
    <section>
        <div class="headerRow">
            <div class="col">User</div>
            <div class="col">email</div>
            <div class="col">Role</div>
            <div class="col">Status</div>
            <div class="col">Action</div>
        </div>
        <?php
       
            // List admins in cols omitting id and hide SuperAdmin edits
            foreach($admin->list() as $adm){
                $id     = $adm->id; unset($adm->id,$adm->last_logged);
                $role   = $adm->role;
                print "<div class='row'>";
                foreach($adm as $k => $v){ 
                    switch($k){
                        case "role":
                        break;
                        case "role_id":
                            print "<div class='col role-id' data-role-id='$v'>".$adm->role."</div>";
                        break;
                        case "status":
                            print $v == 0 ? "<div class='col $k' data-status=$v >Active</div>" : "<div class='col $k' data-status=$v>Inactive</div>";
                        break;
                        default:
                        print "<div class='col $k' >$v</div> ";    
                    }
                    
                }

                // only SuperAdmins can edit. 
                if($admin->role == "SuperAdmin") print "<div class='col aEdit' data-admin-id='".$id. "'></div>";
                print "</div>";
            }
        ?>
        </section>

        <div class="subMenuContainer"> 
        <div data-admin-id="<?php print $admin->admin_id; ?>" class="addAdmin">New Role</div>
    </div>

        </div>

</div><!-- content --> 


<!-- FOOTER -->
<?php echo $menu->footer(); ?>
<script src="js/admin.js"></script>


<!-- Edit Admin dialog HTML-->
<div id="aEdit"  hidden="hidden">
    <div class="dataContainer">
        <input type="hidden" id="admin_id">
        <div class="inputContainer">
            <label for="adminName">Name</label>
            <input type="text" id="adminName">
         </div>
        <div class="inputContainer">
            <label for="email">Email/Username</label>
            <input type="text" id="email"> 
        </div>
        <div class="inputContainer">
            <label for="role">Role</label>
             <select id="role"><select>
        </div>
       <div class="inputContainer">
         <input type="checkbox" id="status">
         <label class="checkbox">Disable this account</label>
       </div>
    </div>
</div>

<!-- New Role dialog HTML  -->
<div id="addRole" hidden="hidden">
        <div class="dataContainer">
            <input type="hidden" id="admin_id">
            <div class="inputContainer">
                <label for="adminName">Name</label>
                <input type="text" id="adminName">
            </div>
            <div class="inputContainer">
                <label for="email">Email</label>
                <input type="text" id="email">
            </div>
            <div class="inputContainer">
                <label for="pass">Password</label>
                <input type="text" id="pass">
            </div>
            <div class="inputContainer">
                <label for="role">Role</label>
                <select id="role"></select>
            </div>
        </div>
</div>

</body>
</html>