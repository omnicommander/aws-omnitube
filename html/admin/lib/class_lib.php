<?php
require($_SERVER['DOCUMENT_ROOT']. '/functions/db.php');

class Admin {

    public function __construct ($name, $role ) {
        if( isset ($_SESSION['adminName'])){
            $this->name = $_SESSION['adminName'];
            $this->lastOn = $_SESSION['last_logged'];
            $this->admin_id = $_SESSION['admin_id'];
            
            global $mysqli;
            $query="Select role from `Roles` where id=". $_SESSION['role'];
            $result = $mysqli->query($query);
            $value = mysqli_fetch_object($result);
            $this->role = $value->role;

        }

      }

    //  List Admin accounts
    //  ==============================
      function list( $admins=array()){
          global $mysqli;
          $sql = "SELECT A.id ,A.adminName, A.email, DATE_FORMAT(A.last_logged,'%m/%d/%Y %H:%i') as last_logged, R.role, A.status FROM Admin A JOIN Roles R on R.id=A.role";
          $result = $mysqli->query($sql);
          while($row = $result->fetch_object()){ array_push($admins, $row); }

     $this->list =(object) $admins;
     return $this->list;
            
      }

    // Admin login function
    // ===============================  
      function login($email, $pass){
    
        global $mysqli;
        $encoded = crypt($pass, '$2a$07$YourSaltIsA22ChrString$');
        $stmt    = $mysqli->prepare("SELECT * FROM Admin WHERE status='0' AND email = ?" );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object();
        
        // Verify user password and set $_SESSION
        if ( password_verify( $pass, $user->pass ) ) {
            $_SESSION['admin_id']       = $user->id;
            $_SESSION['adminName']      = $user->adminName;
            $_SESSION['role']           = $user->role;
            $_SESSION['last_logged']    = $user->last_logged;    

            // update last_logged for Admin
            $mysqli->query("Update Admin SET `last_logged` = NOW() WHERE id='" . $user->id ."'");
            header('location:/admin/dashboard.php');

        }else{
            header('location:/admin/index.php?login');
        }
    
    }

    function newAdmin( $adminName, $email, $pass, $role ){
    
        global $mysqli; 
        $encoded    = crypt($pass, '$2a$07$YourSaltIsA22ChrString$');
        $sql        = "INSERT IGNORE INTO Admin ( `adminName`, `role`, `email`, `pass` ) VALUES ('$adminName',$role,'$email','$encoded')";
    
        if ($mysqli->query($sql) === TRUE) {                
            header('location:/admin/panel.php');
        } else {
           return "Error: " . $sql . "<br>" . $mysqli->error;
        }
    
    }

    // Update Admin account info
    function update( $post_array ){
        global $mysqli;
        extract($post_array);

        $sql = "UPDATE `Admin` SET `adminName`='$adminName',`email`='$email',`role`='$role',`status`= $status WHERE `id` = '$id'";

        if(!$result = $mysqli->query($sql)){ $this->update = array('error' => 'update failed.'); }
        $this->update = $mysqli->affected_rows;
        $this->sql = $sql;
        return $this->sql;
    }

}

class Customer {  
    
    var $customers, $customer;
   
     // Display all customers
    // ** role based, if ADMIN show all, else only assigned admin_id for logged in $_SESSION
    // @TODO: get total clients deployed for customer
    
    function fetchAllCustomers($admin){
        global $mysqli;
        $customers  = array();
        $client = new Client();

        $query      = "SELECT C.id AS customer_id, C.customer_name, A.adminName AS admin_name,
                        DATE_FORMAT(C.date_updated,'%m/%d/%Y') AS customer_date_updated,
                        C.customer_website_url,C.customer_contact_name,C.customer_contact_email, C.customer_contact_phone, C.status AS customer_status
                       FROM `Customer` C 
                       JOIN `Admin` A on A.id=C.admin       
                       WHERE C.status = 1 ";

                       // Limit query to assigned manager only, if not SuperAdmin
                       if( $admin->role != 'SuperAdmin' ) $query=$query. "AND C.admin= ".$_SESSION['admin_id'];

        if(!$result = $mysqli->query($query)){
            $this->customers = array('error' => 'No result!');
            return $this->customers;
        }
        // load up payload and spew data forth into the heavens, and singing "Run To The Hills!"
        while($row = $result->fetch_object()){ 
               array_push($customers, $row); 
        }
        $this->customers =(object) $customers;
        return $this->customers;
    }
    
    // fetch single customer info
    // ==========================

    function fetchCustomer($id,$data=array()){
        global $mysqli;
        if(! $result = $mysqli->query(
            "SELECT customer_name, customer_contact_email, customer_contact_phone, customer_website_url,
            DATE_FORMAT(date_updated,'%m/%d/%Y') AS created ,A.adminName 
            from Customer C
            JOIN Admin A on A.id=C.admin
            WHERE C.id IN('$id')")) {
            $this->customer = array('error' => 'no results');
        }
        while($row = $result->fetch_object()){array_push($data, $row);}
            $this->customer = (object) $data;
        return $this->customer;
    }

    // fetch campaigns by Customer ID
    // ===============================

    function fetchCustomerCampaigns( $customer_id, $data=array() ){
        global $mysqli;
        
        $result = $mysqli->query("SELECT C.campaign_id,C.campaign_name, CL.PI_UID,
                                    DATE_FORMAT(C.created,'%m/%d/%Y') AS created, A.adminName,
                                    IF(C.status=1,'Active','Inactive') as status
                                    FROM Campaign C
                                    JOIN Admin A on A.id=C.admin_id
                                    JOIN Client CL on CL.campaign_id=C.campaign_id
                                    WHERE C.customer_id IN($customer_id)");

        while($row = $result->fetch_object()) { array_push($data, $row); }

        $this->campaigns = $data;
        return (object) $this->campaigns;
    }

    function ip_addrOfClien($client_id){
        global $mysqli;
        
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

// Insert Campaign record (client)
// =================================
function insertCampaign( $post_array ){
    global $mysqli;
    extract( $post_array );

    mysqli_autocommit($mysqli, FALSE);

    $sql = "INSERT INTO Campaign (`customer_id`, `campaign_name`, `status`, `admin_id`) 
                VALUES ('$customer_id','$campaign_name','1', '$admin_id' )";

if ($result = $mysqli->query($sql)) {
    $campaignID = $mysqli->insert_id; // get insertId of Campaign for campaign_id    
    $sqlClient = "INSERT INTO Client (`PI_UID`, `customer_id`, `campaign_id`) 
                    VALUES ('$client_id','$customer_id','$campaignID')";
    
    if(!$clInsert = $mysqli->query($sqlClient) ){
        $this->insert = array('error' => 'failed on clInsert');
        return $this->insert;
    }

    $this->insert = $mysqli->insert_id;
       
}
  if (!mysqli_commit($mysqli)) { //commit transaction
      print("Table saving failed");
      exit();
  }

  return $this->insert;

}


// insert Customer record
// ==============================
function insertCustomer( $post_array ){
    global $mysqli;
    extract( $post_array );

    $sql = "INSERT INTO Customer (`customer_name`, `customer_contact_name`, `customer_contact_email`,`customer_website_url`, `customer_contact_phone`, `admin`, `status`)
            VALUES ('$customer_name','$customer_contact_name','$customer_contact_email','$customer_website_url', '$customer_contact_phone','$admin','$status')";

    if(!$result = $mysqli->query($sql)){
        $this->inserted = array('error' => 'update failed.');
    }
    $this->inserted = $mysqli->insert_id;
        return $this->inserted;
    
    }

    // update Customer record data
    // ==================================
    function updateCustomer( $post_array ){
        global $mysqli;
    
        extract($post_array);

            $sql = "Update Customer SET 
                customer_contact_name   = '$customer_contact_name',
                customer_contact_email  = '$customer_contact_email',
                customer_website_url    = '$customer_website_url',
                customer_contact_phone  = '$customer_contact_phone'
                WHERE id = '$customer_id'";

            if(!$result = $mysqli->query($sql)){ $this->update = array('error' => 'update failed.'); }
            $this->update = $mysqli->affected_rows;
            
            return $this->update;
        }
    
} // Customer class ends here.



// Client class
// ============================
class Client{
    var $clientCount, $monitor, $videos;

    // Get count of clients for a customer
    // ====================================

    function fetchClientCount( $customer_id) {
        global $mysqli;
        $sql = "SELECT COUNT(*) as clientCount FROM Client WHERE customer_id IN('$customer_id')";
        if(!$result = $mysqli->query($sql)){
            $this->clientCount = array('error' => 'no find! ', 'sql' => $sql );
            return $this->clientCount;
        }
          $this->clientCount = mysqli_fetch_object($result);
          return $this->clientCount;       
    }


    // Monitor function per client_id  
    // @return 
    // --------------------------------------------

    function monitor( $post_array , $data=array() ){
        global $mysqli;
        $client = new Client();
        extract( $post_array );
        
        $sql = "SELECT * from Monitor WHERE `client_id` IN ('$client_id') ORDER BY `request_date`";

        if( !$request = $mysqli->query($sql) ){
            $this->monitor = array('error' => 'Not Found', 'sql' => $sql);
            return $this->monitor;
        }

        while($row = $request->fetch_object()) { 
            array_push( $data, $row); 
        }

        $this->monitor = $data;
        return $this->monitor;
    }

    function ipGeo($ip){

        $url = "http://ipinfo.io/{$ip}/geo?token=dfa106283c3f98";

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_URL             => $url,
            CURLOPT_USERAGENT       => 'TubeCommander -1a'
        ]);
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
		curl_close($curl);

        $this->ipGeo = json_decode($resp, true);
        return $this->ipGeo;
    }

    // --------------------------------------------------------------
    // getVideos -- called by pi_callHome.php
    // @var, @var
    // @return array of videoId's for a clientId request
    // $ipaddr is geoLocated and stored in Monitor table
    //         for reporting puposes and backtrack of client activity
    // --------------------------------------------------------------
    function getVideos($clientId, $ipaddr, $videos=array() ){
        global $mysqli;

        $client = new Client();
        
        // geo object from ip_addr
        $geo    = (object) json_decode( json_encode( $client->ipGeo($ipaddr) , true),true);
        $coords = explode(',', $geo->loc);
        $mysqli->query("INSERT INTO Monitor (`client_id`,`ip_addr`,`request`,`city`,`state`,`lat`,`lng`) 
                        VALUES ('$clientId','$ipaddr','pi_getVideos','" . $geo->city."','".$geo->region."','".$coords[0]."','".$coords[1]."' )");
    
        $query = "SELECT V.* FROM `Client` C 
                    JOIN Campaign CA on CA.campaign_id=C.campaign_id
                    JOIN Video V on V.campaign_id=CA.campaign_id
                    WHERE C.PI_UID='$clientId' AND CA.status=1";
  
        // failed query just return false
        if(!$result = $mysqli->query($query)){ return false; }
    
        // successful query
        while($row = $result->fetch_assoc()){ array_push( $videos, $row); }
    
        // no records?
        if(mysqli_num_rows($result) === 0 ){
        //   return array('status'=> 404, 'message' => "No results found for $customer");
        return $query;
        }else{
            $this->videos = $videos;
            return $this->videos;
        }   

    }

    
        


}




// Campaigns Class
// ====================================

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

    // Dashboard menu functions
    // ===========================

      function dashboard(){
        global $admin; 

        //  default menu string
         $this->menu = "<ul class='menu ".$admin->role ."'><li class='home'><a href='/'>Home</a></li>";
        
         // admin exception
          $this->menu .= $admin->role == 'SuperAdmin' ? "<li class='dashboard'><a href='/admin/dashboard.php'>Customers</a></li>
          <li class='register'><a href='/admin/panel.php'>Admin</a></li>
          <li class='logout'><a href='/admin/logout.php'>Logout</a></li></ul>" : "<li class='logout'><a href='/admin/logout.php'>Logout</a></li></ul>";
          
          return $this->menu;
      }

    // Admin panel menu render
    // =======================
      function panel(){
          global $admin; 

        $this->menu = "<ul class='menu ".$admin->role ."'><li class='home'><a href='/'>Home</a></li>";
        $this->menu .= $this->role == 'SuperAdmin' ? "<li class='dashboard'><a href='/admin/dashboard.php'>Dashboard</a></li>
        <li class='register'><a href='/admin/register'>New User</a></li>
        <li class='logout'><a href='/admin/logout.php'>Logout</a></li></ul>" : "<li class='logout'><a href='/admin/logout.php'>Logout</a></li></ul>";
        
        return $this->menu;
      }

    // Footer render
    // ======================   
       function footer(){
        return '<footer class="footer"> &copy; Omnicommander '. date('Y'). ' v0.1</footer>';
    }

}
