<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// No unathorized access allowed
session_start();
if (!isset( $_SESSION['adminName'] ) ) { header('location:/admin/login.php'); }

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
        <title>OmniTube Service</title>
        <meta name="description" content="The Service to the OmniTube Clients">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/admin.css">
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
    </head>
    <body>

<?php

// show a little class will ya?
$customers  = new Customer(); 
$campaigns  = new Campaign();
$videos     = new Video();
$admin      = new Admin( $_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );

$menu       = new menu();

?>
<div class="adminSession">
    <span class="session"><?php  echo $admin->name. " Last On: ". $admin->lastOn; ?></span>
    <?php echo $menu->dashboard(); ?>  
</div>


<div class="content">

<header class="dashboard"><h3>Customers</h3></header>
<div class="customerContainer">
    <section>
        <div class="headerRow">
            <div class="col">Customer</div>
            <div class="col">Last Update</div>
            <div class="col">Website</div>
            <div class="col">Contact</div>
            <div class="col">E-Mail</div>
            <div class="col">Phone</div>
            <div class="col">Action</div>
        </div>
    
        <?php
            foreach($customers->fetchAllCustomers($admin) as $cust){
                $cid    = $cust->customer_id;
                $cName  = $cust->customer_name;
                
                // lose status for this version - @TODO: configure customer status
                unset( $cust->customer_status );
                
                echo '<div class="row">';

                foreach($cust as $key => $value){
                    switch($key){
                        case 'customer_name':
                        break;
                        case 'customer_id':
                            echo "<div class='col $key' data-customer-name=\"$cName\" ><a href='customer.php?id=$value'>".strtoupper( $cName ) . "</a></div>";
                        break;
                        default:
                            echo "<div class='col $key' >$value</div>";
                    }
                }
             
                echo "<div class='col'><span class='cEdit' data-customer-id='$cid'>Edit</span> <span class='cDelete' data-customer-id='$cid'>Delete</span></div>";
                
                echo "</div><!-- row -->";
                
            }

            ?>
    </section>

    <div class="subMenuContainer"> 
        <div data-admin-id="<?php echo $admin->admin_id; ?>"" class="addCustomer">Add Customer</div>
    </div>


</div><!-- customerContainer -->

</div><!-- content -->

<?php echo $admin->footer(); ?>


<script src="js/customer.js"></script>

<!-- Edit Customer dialog HTML-->
<div id="cEdit" title="Edit Customer" hidden="hidden">
    <div class="dataContainer">
        <div class="inputContainer">Contact Name <input type="text" id="customer_contact_name"> </div>
        <div class="inputContainer">Contact Email <input type="text" id="customer_contact_email"> </div>
        <div class="inputContainer">Contact Phone <input type="text" id="customer_contact_phone"> </div>
        <div class="inputContainer">Customer Website <input id="customer_website_url" type="text" ></div>
    </div>
</div>

<!-- Add New Customer dialog HTML -->
<div id="addCustomer" title="Add New Customer" hidden="hidden">
    <div class="dataContainer">
        <input type="hidden" name="admin" id="admin">
        <div class="inputContainer">Customer Name <input type="text" id="customer_name">  </div>
        <div class="inputContainer">Contact Name <input type="text" id="customer_contact_name"> </div>
        <div class="inputContainer">Contact Email <input type="text" id="customer_contact_email"> </div>
        <div class="inputContainer">Contact Phone <input type="text" id="customer_contact_phone"> </div>
        <div class="inputContainer">Customer Website <input id="customer_website_url" type="text" ></div>
    </div>
</div>


</body>
</html>