<?php
require($_SERVER['DOCUMENT_ROOT']. '/functions/db.php');
require($_SERVER['DOCUMENT_ROOT'] .'/functions/admin.php');

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
    
    function fetchAllCustomers($admin){
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
    
    // fetch single customer info, including campaigns, videos
    function fetchCustomer($id,$data=array()){
        global $mysqli;
        if(! $result = $mysqli->query(
            "select * from Customer C
            WHERE C.id IN('$id')")) {
            $this->customer = array('error' => 'no results');
        }
        while($row=$result->fetch_object()){array_push($data, $row);}
        $this->customer = (object) $data;
        return $this->customer;
    }

    // fetch campaigns by Customer ID
    function fetchCustomerCampaigns( $customer_id, $data=array() ){
        global $mysqli;
        $result = $mysqli->query("SELECT * from Campaign WHERE customer_id IN($customer_id)");
        while($row = $result->fetch_object()){array_push($data, $row);}
        $this->campaigns=$data;
        return (object) $this->campaigns;
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
        $this->campaigns = $data;
        return $this->campaigns;
    }
    
}

// Video Class
class Video{
    var $videos, $video;
    
    function fetchCampaignVideos($campaignId){
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




class menu{
    
    var $menu, $link;

    public function __construct () {
                
        global $mysqli;
        $query="Select role from `Roles` where id=". $_SESSION['role'];
        $result = $mysqli->query($query);
        $value = mysqli_fetch_object($result);
        $this->role = $value->role;
        
      }

      function main(){
           
          
          $this->menu = "<ul class='menu ".$this->role ."'><li><a href='/'>Home</a></li>";
          $this->menu .= $this->role == 'Admin' ? "<li><a href='/admin/register'>Register</a></li><li><a href='/admin/logout.php'>Logout</a></li></ul>": "</ul>";

          return $this->menu;
      }
}