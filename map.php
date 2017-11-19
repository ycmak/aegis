<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"  xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>gothere.sg - API Examples - Basic map</title>
	<style>
		body{
			margin: 0;
		}
	</style>
</head>
<body>
    <div id="map" style="width:25000px;height:15000px;"></div>
    <script type="text/javascript" src="http://gothere.sg/jsapi?sensor=false"></script>
    <script type="text/javascript">
        gothere.load("maps");
        function initialize() {
            if (GBrowserIsCompatible()) {
                // Create the Gothere map object.
            	var map = new GMap2(document.getElementById("map"));
            	// Set the center of the map.
            	map.setCenter(new GLatLng(1.29297, 103.8523), 16);
            	// Add zoom controls on the top left of the map.
            	map.addControl(new GSmallMapControl());
            	// Add a scale bar at the bottom left of the map.
            	map.addControl(new GScaleControl());
              }
        }
       gothere.setOnLoadCallback(initialize);
    </script>

</body>
</html>