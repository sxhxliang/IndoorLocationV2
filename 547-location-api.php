<?php
    //Configuration
    $path_to_discovered = "/var/www/scripts/discovered.sh";
    $path_to_associated = "/var/www/scripts/associated.sh";
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
    
      $mac_address = strtolower($mac_address_raw);
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
            $one_data['Connection'] = "Discovered";
            $one_data['Type'] = $linearr[2];
        	$one_data['Channel'] = $linearr[3];
        	$one_data['Confirmed-Channel'] = $linearr[4];
         if(!preg_match('/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/', $linearr[5]){
         		$one_data['SSID'] = "SSID";
        		$one_data['BSSID'] = $linearr[5];
        		$one_data['Last'] = $linearr[6];
        		$one_data['Previous'] = $linearr[7];
        		$one_data['Current'] = $linearr[8];
        		$one_data['Packets_Rx'] = $linearr[9];
        		$one_data['RF_Band'] = $linearr[10];
        		$one_data['Name'] = $linearr[11];
        		
       		 }
        else{
        		$one_data['SSID'] = $linearr[5];
        		$one_data['BSSID'] = $linearr[6];
        		$one_data['Last'] = $linearr[7];
        		$one_data['Previous'] = $linearr[8];
        		$one_data['Current'] = $linearr[9];
        		$one_data['Packets_Rx'] = $linearr[10];
        		$one_data['RF_Band'] = $linearr[11];
        		$one_data['Name'] = $linearr[12];
        
        	}
        	array_push($finalData,$one_data);
        }
            
    }
           $output = array();
    exec($path_to_associated." ".$mac_address, $output);
    //Parse output
 
    
    foreach($output as $line){
        //Take the line and make it into an array exploding by commmas
        $linearr = explode(",",$line);
        if($linearr[1] == $mac_address){
            //We found a line of data, parse it
            $one_data = array();
            $one_data['Connection'] = "Associated";
            $one_data['Type'] = $linearr[2];
            if($linearr[4] != "ASSOCIATED"){
            $one_data['SSID'] = "SSID";
            $one_data['State'] = $linearr[3];
            $one_data['Encrypt'] = $linearr[4];
            $one_data['Packets_Rx'] = $linearr[5];
            $one_data['Packets_Tx'] = $linearr[6];
            $one_data['Last_Seen'] = $linearr[7];
            $one_data['Previous'] = $linearr[8];
            $one_data['Current'] = $linearr[9];
            $one_data['RF_Band'] = $linearr[10];
            $one_data['Name'] = $linearr[11];
            }
            else{
            $one_data['SSID'] = $linearr[3];
            $one_data['State'] = $linearr[4];
            $one_data['Encrypt'] = $linearr[5];
            $one_data['Packets_Rx'] = $linearr[6];
            $one_data['Packets_Tx'] = $linearr[7];
            $one_data['Last_Seen'] = $linearr[8];
            $one_data['Previous'] = $linearr[9];
            $one_data['Current'] = $linearr[10];
            $one_data['RF_Band'] = $linearr[11];
            $one_data['Name'] = $linearr[12];

            }
            array_push($finalData,$one_data);
            }
            
        }
        
    $json_output = json_encode($finalData);
    echo $json_output;
?>