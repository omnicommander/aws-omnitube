<?php
require('../functions/db.php');
require('../functions/admin.php');

class Admin {

    public function __construct ($name, $role ) {
        $this->name = $_SESSION['adminName'];
        $this->lastOn = $_SESSION['last_logged'];
        
        global $mysqli;
        $query="Select role from `Roles` where id=". $_SESSION['role'];
        $result = $mysqli->query($query);
        $value = mysqli_fetch_object($result);
        $this->role = $value->role;

      }
}



class Customer {  
    
    var $customers, $customer;
    
     // all customers, returns as object
    // role based, if ADMIN show all, else only assigned admin_id for logged in $_SESSION
    
    function customers($admin){
        global $mysqli;
        $customers  = array();
        
        $query      = "SELECT C.id customer_id, C.customer_name, C.customer_website_url,C.customer_contact_phone,C.status, A.adminName FROM `Customer` C 
                       JOIN `Admin` A on A.id=C.admin       
                       WHERE C.status = 1 ";
                       
                       // Limit to assigned managers' customers. 
                       if( $admin->role != 'Admin' ) $query=$query. "AND C.admin= ".$_SESSION['admin_id'];


        if(!$result = $mysqli->query($query)){
            $this->customers = array('error' => 'No result!');
            return $this->customers;
        }
        // load up payload and spew
        while($row = $result->fetch_object()){ array_push($customers, $row);}
        $this->customers =(object) $customers;
        return $this->customers;
    }
    
} // Customer class


// Campaigns Class
class Campaign{
    var $campaigns, $campaign;
    
    function campaigns($customerId){

        global $mysqli;
        $data = [];
        $query = "SELECT C.*, Cl.PI_UID from Campaign C
                  JOIN Client Cl on Cl.campaign_id=C.campaign_id
                  WHERE C.customer_id='$customerId'";
       
        if(!$result = $mysqli->query($query)){
            $this->campaigns = array('error' => 'nodo find');
            return $this->campaigns;
        }
        // successful query
        while($row = $result->fetch_object()){ array_push($data, $row);}
        $this->campaigns =(object) $data;
        return $this->campaigns;
    }
    
}

// Video Class
class Video{
    var $videos, $video;
    function videos($campaignId){
        global $mysqli;
        $data = [];
        $query = "SELECT * from Video where campaign_id='$campaignId'";
       
        if(!$result = $mysqli->query($query)){
            $this->videos = array('error' => 'nodo find');
            return $this->videoss;
        }
        // successful query
        while($row = $result->fetch_object()){ array_push($data, $row);}
        $this->videos =(object) $data;
        return $this->videos;
    }
}