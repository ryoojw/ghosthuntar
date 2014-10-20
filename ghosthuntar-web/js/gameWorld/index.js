$(function() {

	var map, geocoder, markerImage;
	var blinkyImage, pinkyImage, inkyImage, clydeImage;
	var markerCluster, totalMarkers = [];
	
	initialise();
	
	function initialise() {
	
		geocoder = new google.maps.Geocoder();
	  
	  var mapOptions = {
	    zoom: 4,
	    center: new google.maps.LatLng(52.954783, -1.158109),
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	  }
	  
	  map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	  
	  var infowindow = new google.maps.InfoWindow({
			content: ''
		});
	  
	  blinkyImage = new google.maps.MarkerImage('http://www.ghosthuntar.com/images/Blinky_marker.png',
			new google.maps.Size(63, 82),
			new google.maps.Point(0, 0),
			new google.maps.Point(31, 74)
		);
		
		pinkyImage = new google.maps.MarkerImage('http://www.ghosthuntar.com/images/Pinky_marker.png',
			new google.maps.Size(63, 82),
			new google.maps.Point(0, 0),
			new google.maps.Point(31, 74)
		);
		
		inkyImage = new google.maps.MarkerImage('http://www.ghosthuntar.com/images/Inky_marker.png',
			new google.maps.Size(63, 82),
			new google.maps.Point(0, 0),
			new google.maps.Point(31, 74)
		);
		
		clydeImage = new google.maps.MarkerImage('http://www.ghosthuntar.com/images/Clyde_marker.png',
			new google.maps.Size(63, 82),
			new google.maps.Point(0, 0),
			new google.maps.Point(31, 74)
		);
		
		myClusterOptions = {
			gridSize: 	 35,
			zoomOnClick: false,
			styles: [{
				height: 58,
				width:  80,
				url: 		"http://www.ghosthuntar.com/images/Ghosts_marker.png"
			}]
		}
		
		markerCluster = new MarkerClusterer(map, totalMarkers, myClusterOptions);
	  
		$.ajax({
		
			cache:   false,
			type:    'POST',
			url:     '/Ghost%20HuntAR/ghost/json',
			success: function(data) {
			
				var obj = $.parseJSON(data);
				console.log(obj);
				
				console.log(obj.ghosts.length);
				
				for (var i = 0; i < obj.ghosts.length; i++) {
				
					var latLng = new google.maps.LatLng(obj.ghosts[i].latitude, obj.ghosts[i].longitude);
					
					
					if (obj.ghosts[i].type == "blinky") {
					
						markerImage = blinkyImage;
						
					} else if (obj.ghosts[i].type == "pinky") {
					
						markerImage = pinkyImage;
						
					} else if (obj.ghosts[i].type == "inky") {
					
						markerImage = inkyImage;
						
					} else if (obj.ghosts[i].type == "clyde") {
					
						markerImage = clydeImage;
						
					}
				
					var marker = new google.maps.Marker({
						icon:		  markerImage,
						position: latLng
					});
					
					bindInfowindow(marker, infowindow, obj.ghosts[i]);
					
					totalMarkers.push(marker);
					
				}
				
				markerCluster.addMarkers(totalMarkers);
				
			}
			
		});
	  
	}
	
	function bindInfowindow(marker, infowindow, ghost) {
		
		google.maps.event.addListener(marker, 'click', function() {
			var contentString = '<div>'+
														ghost.name + '<br>'+
														ghost.latitude + '<br>'+
														ghost.longitude + '<br>'+
													'</div>';
			
			infowindow.setContent(contentString);
  		infowindow.open(map, marker);
		});
		
	}
	
});