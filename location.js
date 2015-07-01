var arguments = process.argv;
var locations = new Array();
for(var i = 2; i < arguments.length; i++){

        var type = concat(arguments[i]);
        i++;
	var lastSeen = concat(arguments[i]);
        i++;
	var name = concat(arguments[i]);

        var location = new LocationData(type,lastSeen,name);
        locations.push(location);
}



function concat(string){

        string = /:(.+)/.exec(string)[1];
        string = /:(.+)/.exec(string)[1];
        return string;
}

function LocationData(type,lastSeen,name){

        this.type = type;
        this.lastSeen = lastSeen;
        this.name = name;

}

var xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'
xml+= '<presence xmlns="urn:ietf:params:xml:ns:pidf" xmlns:gp="urn:ietf:params:xml:ns:pidf:geopriv10" xmlns:ca="urn:ietf:params:xml:ns:pidf:geopriv10:civicAddr" xmlns:gml="http://www.opengis.net/gml"entity="sip:caller@64.131.109.27"> <tuple id="id82848"><status><gp:geopriv><gp:location-info><ca:civicAddress> <ca:country>us</ca:country> <ca:A1>il</ca:A1> <ca:A2>CHICAGO</ca:A2> <ca:A6>35</ca:A6> <ca:PRD>W</ca:PRD> <ca:STS>th</ca:STS> <ca:HNO>10</ca:HNO> <ca:LOC>st</ca:LOC> <ca:FLR>9</ca:FLR> <ca:ROOM>9e-354</ca:ROOM> </ca:civicAddress></gp:location-info><gp:usage-rules/> <gp:method>Manual</gp:method></gp:geopriv> </status> <contact priority="0.8">sip:caller@64.131.109.27</contact> <timestamp>2pm</timestamp></tuple></presence>'


console.log(xml);


console.log(locations);






