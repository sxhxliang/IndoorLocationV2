<?php
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
        <ca:A6>Federal</ca:A6>
        <ca:PRD>S</ca:PRD>
        <ca:HNO>3241</ca:HNO>
        <ca:LOC>st</ca:LOC>
        <ca:FLR>1</ca:FLR>
        <ca:ROOM>Alumni Lounge</ca:ROOM>
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
?>
