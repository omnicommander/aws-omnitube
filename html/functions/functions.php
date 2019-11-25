<?php

// getVideos -- fetch associated customer videos 
function pi_getVideos($customer, $videos=array() ){
    global $mysqli;
    $query = "SELECT V.youtube_id
                FROM Video V
                    JOIN Campaign C on C.campaign_id = V.campaign_id
                    JOIN Customer CU on CU.id=C.customer_id
                WHERE CU.customer_id = '$customer'
                AND C.status = 1";
        
        // failed query just return false
        if(!$result = $mysqli->query($query)){
            return false;
        }

        // successful query
        while($row = $result->fetch_assoc()){
            array_push($videos, $row);
        }

        // no records?
        if(mysqli_num_rows($result) === 0 ){
            return array('status'=> 404, 'message' => "No results found for $customer");
        }else{
            return $videos;
        }   
}