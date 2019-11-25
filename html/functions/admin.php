<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function addAdmin( $adminName, $email, $pass, $role ){
    
    global $mysqli;
    
    $encoded    = crypt($pass, '$2a$07$YourSaltIsA22ChrString$');
    $sql        = "INSERT IGNORE INTO Admin ( `adminName`, `role`, `email`, `pass` ) VALUES ('$adminName',$role,'$email','$encoded')";

    if ($mysqli->query($sql) === TRUE) {
        
        //successful insert of new record
        header('location:/admin/index.php?id='. mysqli_insert_id($mysqli) );

    } else {
       return "Error: " . $sql . "<br>" . $mysqli->error;
    }

}

function AdminLogin($email, $pass){
    
    global $mysqli;
    session_start();

    $encoded = crypt($pass, '$2a$07$YourSaltIsA22ChrString$');
    $stmt = $mysqli->prepare("SELECT * FROM Admin WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_object();
    
    // Verify user password and set $_SESSION
    if ( password_verify( $pass, $user->pass ) ) {
        $_SESSION['admin_id']  = $user->id;
        $_SESSION['adminName'] = $user->adminName;
        $_SESSION['role']      = $user->role;
        $_SESSION['last_logged']    = $user->last_logged;
        
        header('location:/admin/dashboard.php');
    }    

}

