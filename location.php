<?php
function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    }
    else {
        // Return array
        return $d;
    }
}

$mac_address_raw = @$_GET['mac'];
$location_server = "http://10.47.128.156:8080";
$buildingArray = array(
    "RT" => "IIT Research Tower"
);

if($mac_address_raw == ""){
        echo "You need to provide a mac address!";
        exit;
    }
    
    if (!preg_match('/^([a-fA-F0-9]{2}:){5}[a-fA-F0-9]{2}$/', $mac_address_raw)){
       echo "Mac address is invalid";
       exit;
    }
$mac = $mac_address_raw;
//Pull from servers
exec("curl ".$location_server."/?mac=".$mac,$content);
//Parse the $content json into array format
$location_array = objectToArray(json_decode($content[0]));
$location_final = null;
//Take the location array and loop looking for "associated"
foreach($location_array as $location){
    if($location["type"]=="Associated"){
        //WE have a location
        $location_final = $location;
    }
}
if($location_final == null){
    //We only have discovered AP's
    //Loop each of the AP's and convert the array to have more info
    //Find the max and min floor and the most occuring floor
    $max_floor = 0;
    $min_floor = 0;
    $floor_array = array();
    foreach($location_array as &$location){
        $linearr = explode("-",$location["name"]);
        $buildingcode = "";
        $floor = "";
        $room = "";
        //Parse differently based on the count of data
        if(count($linearr) == 5){
            //The ideal format. BLD-FLR-ROOM
            $buildingcode = $linearr[0];
            $floor = $linearr[1];
            $room = $linearr[2];
        }elseif(count($linearr)==4){
            //Double format either BLD-FLR-ROOM or BLD-ROOM
            if(is_numeric($linearr[1])){
                //1 is the floor
                $buildingcode = $linearr[0];
                $floor = $linearr[1];
                $room = $linearr[2];
            }else{
                $buildingcode = $linearr[0];
                $floor = substr($linearr[1], 0, strspn($linearr[1], "0123456789"));
                $room = $linearr[1];
            }
        }elseif(count($linearr)==3){
            //Format is BLD-ROOM-????
            $buildingcode = $linearr[0];
            $floor = substr($linearr[1], 0, strspn($linearr[1], "0123456789"));
            $room = $linearr[1];
        }

        //Now that we have determined the data we need to output it
        $location["BuildingCode"] = $buildingcode;
        $location["Floor"] = $floor;
        $location["Room"] = $room;
        //Determine max and min floors
        if(($floor <= $min_floor)||($min_floor == 0)){
            $min_floor = $floor;
        }
        if($floor >= $max_floor){
            $max_floor = $floor;
        }
        array_push($floor_array,$floor);
    }
    //Find the difference between max floor and min floor
    $floor_difference = $max_floor - $min_floor;
    $final_floor = null;
    if($floor_difference % 2 == 0){
        //even floor diff
        $final_floor = $max_floor - ($floor_difference / 2);
    }else{
        //now we find the floor mode which is our final floor.
        $floor_count = array_count_values($floor_array);
        $final_floor = array_search(max($floor_count), $floor_count);
    }
    //Now that we have final floor we pick the data that has this floor #
    foreach($location_array as $location){
        if($location["Floor"] == $final_floor){
            $location_final = $location;
        }
    }

}else{
    //We have our location, parse it out
    //Parse each line and remove the -
    $linearr = explode("-",$location_final["name"]);
    $buildingcode = "";
    $floor = "";
    $room = "";
    //Parse differently based on the count of data
    if(count($linearr) == 5){
        //The ideal format. BLD-FLR-ROOM
        $buildingcode = $linearr[0];
        $floor = $linearr[1];
        $room = $linearr[2];
    }elseif(count($linearr)==4){
        //Double format either BLD-FLR-ROOM or BLD-ROOM
        if(is_numeric($linearr[1])){
            //1 is the floor
            $buildingcode = $linearr[0];
            $floor = $linearr[1];
            $room = $linearr[2];
        }else{
            $buildingcode = $linearr[0];
            $floor = substr($linearr[1], 0, strspn($linearr[1], "0123456789"));
            $room = $linearr[1];
        }
    }elseif(count($linearr)==3){
        //Format is BLD-ROOM-????
        $buildingcode = $linearr[0];
        $floor = substr($linearr[1], 0, strspn($linearr[1], "0123456789"));
        $room = $linearr[1];
    }

    //Now that we have determined the data we need to output it
    $location_final["BuildingCode"] = $buildingcode;
    $location_final["Floor"] = $floor;
    $location_final["Room"] = $room;
}
if($location_final == null){
    echo "false";
}else{
    //We have location
    echo '<?xml version="1.0" encoding="ISO-8859-1"?>
<presence xmlns="urn:ietf:params:xml:ns:pidf"
    xmlns:gp="urn:ietf:params:xml:ns:pidf:geopriv10"
    xmlns:ca="urn:ietf:params:xml:ns:pidf:geopriv10:civicAddr"
    xmlns:gml="http://www.opengis.net/gml"
    entity="sip:caller@64.131.109.27">
  <tuple id="id82848">
   <status>
    <gp:geopriv>
     <gp:location-info>
       <ca:civicAddress>
        <ca:country>us</ca:country>
        <ca:A1>il</ca:A1>
        <ca:A2>CHICAGO</ca:A2>
        <ca:A6>35</ca:A6>
        <ca:PRD>W</ca:PRD>
        <ca:STS>th</ca:STS>
        <ca:HNO>10</ca:HNO>
        <ca:LOC>st</ca:LOC>
        <ca:FLR>'.$location_final["Floor"].'</ca:FLR>
        <ca:ROOM>'.$location_final["Room"].'</ca:ROOM>
       </ca:civicAddress>
     </gp:location-info>
     <gp:usage-rules/>
     <gp:method>Manual</gp:method>
    </gp:geopriv>
   </status>
  <contact priority="0.8">sip:caller@64.131.109.27</contact>
<timestamp>'.date("c",time()).'</timestamp>
  </tuple>
</presence>';
}

?>
