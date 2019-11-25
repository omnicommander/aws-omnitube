<?php

// getVideos -- fetch associated customer videos 
function pi_getVideos($clientId, $videos=array() ){
    global $mysqli;

     // store request in Monitor
    $ipaddr = getIpAddr();
    $mysqli->query("INSERT INTO Monitor (`client_id`,`ip_addr`,`request`) VALUES ('$clientId','$ipaddr','pi_getVideos')");

    // PSUEDO: Get all videos for the clientId on assigned active campaign.
    
    $query = "SELECT V.*, Cl.campaign_id, C.customer_name,Ca.campaign_name, A.adminName, A.email adminEmail
                FROM `Customer` C 
                JOIN Client Cl on Cl.customer_id=C.id
                JOIN Campaign Ca on Ca.customer_id=C.id
                JOIN Video V on V.campaign_id=Ca.campaign_id
                JOIN `Admin` A on A.id=C.admin
                WHERE Cl.PI_UID='$clientId' and Ca.status=1";
        
        // failed query just return false
        if(!$result = $mysqli->query($query)){ return false; }

        // successful query
        while($row = $result->fetch_assoc()){ array_push($videos, $row); }

        // no records?
        if(mysqli_num_rows($result) === 0 ){
          return array('status'=> 404, 'message' => "No results found for $customer");
        }else{
          return $videos;
        }   
}


function getIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}