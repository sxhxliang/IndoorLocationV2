<?php
    //Configuration
    $path_to_discovered = "/home/admin/scripts/discovered.sh";
    $path_to_associated = "/home/admin/scripts/associated.sh";
    //Take input into the script as mac
    $mac_address_raw = @$_GET['mac'];
    if($mac_address_raw == ""){
        echo "You need to provide a mac address!";
        exit;
    }
    
    if (!preg_match('/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/', $mac_address_raw)){
       echo "Mac address is invalid";
       exit;
    }
    //All validations completed. $mac_address_raw is validated to be in format 00:00:00:00:00:00
    $mac_address = $mac_address_raw;
    //Create an output array
    $output = array();
    //Final data will be arrays of format. {Last Seen, Name, type: Discovered/Associated}
    $finalData = array();
    //Run the command to get output for discovered
    exec($path_to_discovered." ".$mac_address, $output);
    //Parse output
    foreach($output as $line){
        //Take the line and make it into an array exploding by commmas
        
        $linearr = explode(",",$line);
        if($linearr[1] == $mac_address){
            //We found a line of data, parse it
            $one_data = array();
            $one_data['type'] = "Discovered";
            foreach($linearr as $data){
                //Find Last seen
                if(strpos($data,"00d") === 0){
                       //Found it!
                        $one_data['lastSeen'] = $data;
                }
                if(strpos($data,"RT") === 0){
                    //Found the location in the tower
                    $one_data['name'] = $data;
                }
            }
            array_push($finalData,$one_data);
        }
        
    }
    exec($path_to_associated." ".$mac_address, $output);
    //Parse output
    foreach($output as $line){
        //Take the line and make it into an array exploding by commmas
        
        $linearr = explode(",",$line);
        if($linearr[1] == $mac_address){
            //We found a line of data, parse it
            $one_data = array();
            $one_data['type'] = "Associated";
            foreach($linearr as $data){
                //Find Last seen
                if(strpos($data,"00d") === 0){
                       //Found it!
                        $one_data['lastSeen'] = $data;
                }
                if(strpos($data,"RT") === 0){
                    //Found the location in the tower
                    $one_data['name'] = $data;
                }
            }
            array_push($finalData,$one_data);
        }
        
    }
    $json_output = json_encode($finalData);
    echo $json_output;
?>




