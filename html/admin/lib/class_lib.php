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
            "SELECT customer_name, customer_contact_email, customer_contact_phone, customer_website_url,
            DATE_FORMAT(date_created,'%m/%d/%Y') AS created ,A.adminName 
            from Customer C
            JOIN Admin A on A.id=C.admin
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
        
        $result = $mysqli->query("SELECT C.campaign_id,C.campaign_name, 
                                  DATE_FORMAT(C.created,'%m/%d/%Y') AS created,A.adminName,
                                  IF(C.status=1,'Active','Inactive') as status
                                  FROM Campaign C
                                   JOIN Admin A on A.id=C.admin_id
                                   WHERE C.customer_id IN($customer_id)");

        while($row = $result->fetch_object()) { array_push($data, $row); }

        $this->campaigns = $data;
        return (object) $this->campaigns;
    }

    function fetchCustomerCampaignVideos($campaignId, $data=array()){
        global $mysqli;

        $result = $mysqli->query( "SELECT video_id, video_title, youtube_id, 
                                   DATE_FORMAT(date_created,'%m/%d/%Y') AS date_created 
                                   FROM Video WHERE campaign_id IN ('$campaignId')");

        while($row = $result->fetch_object()) { array_push($data, $row); }
        
        $this->videos = $data;
        return (object) $this->videos;
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
        $query = "SELECT * FROM Video 
                  WHERE campaign_id='$campaignId'";
       
        if(!$result = $mysqli->query($query)){
            $this->videos = array('error' => 'nodo find');
            return $this->videoss;
        }
        // successful query
        while($row = $result->fetch_object()){ array_push($data, $row);}
        $this->videos =(object) $data;
        return $this->videos;
    }

    // update a video entry
    function updateVideo( $post_array ){
        global $mysqli;
        extract($post_array);
        $sql = "Update Video SET video_title='$video_title', youtube_id='$youtube_id' WHERE video_id IN ('$video_id')";
        if(!$result = $mysqli->query($sql)){
            $this->update = array('error' => 'update failed.');
        }
        $this->update = true;
        return $this->update;
    }

    // insert a new video into Video table 
    function insertVideo( $post_array ){
        global $mysqli;
        extract( $post_array );
        
        $sql = "INSERT INTO Video (video_title, youtube_id, campaign_id ) VALUES ('$video_title', '$youtube_id', '$campaign_id')";

        if( !$result = $mysqli->query($sql) ){
            $this->insert_id = array('error' => 'insert failed.');         
        }
        $this->insert_id = $mysqli->insert_id;
        return $this->insert_id;

    }

    // delete a video record
    function deleteVideo( $post_array ){
        global $mysqli;
        extract($post_array);

        $sql = "DELETE FROM Video WHERE video_id IN('$video_id')";
        if( !$result = $mysqli->query($sql) ){
            $this->delete = array('error' => 'delete failed.');                     
        }
        $this->rows = $mysqli->affected_rows;
        return $this->rows;
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

      function dashboard(){
          $this->menu = "<ul class='menu ".$this->role ."'><li class='home'><a href='/'>Home</a></li>";
          $this->menu .= $this->role == 'Admin' ? "<li class='dashboard'><a href='/admin/dashboard.php'>Dashboard</a></li>
          <li class='register'><a href='/admin/register'>Register</a></li>          
          <li class='logout' ><a href='/admin/logout.php'>Logout</a></li></ul>": "</ul>";

          return $this->menu;
      }
}