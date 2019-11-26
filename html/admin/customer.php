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
ini_set('display_errors', 1);
error_reporting(E_ALL);
// No unathorized access allowed
session_start();
if (!isset( $_SESSION['adminName'] ) ) { header('location:/admin/login.php'); }
// show a little class will ya?
include('lib/class_lib.php');

$customers  = new Customer(); 
$campaigns  = new Campaign();
$videos     = new Video();
$admin      = new Admin($_SESSION['adminName'], $_SESSION['role'], $_SESSION['last_logged'] );
$menu       = new menu();
?>

<div class="adminSession">
    <?php  echo $admin->name. " Last On: ". $admin->lastOn . $menu->main(); ?>  
</div>

<div class="container">
<h3>Customer</h3>

<pre> <?php print_r( $customers->fetchCustomer($_GET['id']) ); ?> </pre>

<h3>Campaigns</h3>

<pre><?php print_r( $customers->fetchCustomerCampaigns($_GET['id'])); ?> </pre>