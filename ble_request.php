<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

    include './includes/dbhelper.php';
    include './includes/utils.php';
    include './includes/constants.php';
    include './includes/controller.php';


 
    //m_log("TAEYU".PHP_EOL);
    //Make sure that it is a POST request.
    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
       // throw new Exception('Request method must be POST!');
        print("The server is running");
    }

    //Make sure that the content type of the POST request has been set to application/json
    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
    if(strcasecmp($contentType, 'application/json') != 0){
        throw new Exception('Content type must be: application/json');
    }

    //Receive the RAW post data.
    $content = trim(file_get_contents("php://input"));
    //m_log("RAW DATA = $content".PHP_EOL);

    //Attempt to decode the incoming RAW post data from JSON.
    $decoded = json_decode($content, true); //JSON object

    //If json_decode failed, the JSON is invalid.
    if(!is_array($decoded)){
        throw new Exception('Received content contained invalid JSON!');
    }
    //Process the JSON***********************
    //GET MAC ADDRESS OF GATEWAY --> CLUB_DEVICE
    $clubDevice = null;
    $clubDeviceId = null;
    foreach($decoded as $k=>$v) {
       //m_log("TY");
        $type = $v['type'];
        $mac = $v['mac'];
        $curRssi = $v['rssi'];
        //m_log("DATA: MAC=".$mac.", type=".$type.",".$curRssi);

        //m_log("TY1");
        if (strcmp($type, $CONST_GATEWAY)==0) { // if it is a gateway, look for club_device
            $clubDevice = getClubDevice($mac);
            //m_log("GATEWAY: MAC=".$mac.", type=".$type.",".$curRssi);
            $clubDeviceId = $clubDevice->getId();
            //m_log($clubDevice->__toString());
        } else {
            //if(strpos($mac, '0017') !==false) {
            //m_log("BAND: BLE MAC=".$mac.", clubDeviceId=".$clubDeviceId.",".$curRssi);
            trackUser($mac,$clubDeviceId, $curRssi);
            //}
        }
    }
?>
        
        
