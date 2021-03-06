$(function() {

	var address, geocoder, marker, map, places, info_window;
	var street_address, street_number, route, locality, administrative_area_level_1, administrative_area_level_2, administrative_area_level_3, postal_code, country;
	var latitude, longitude;

	initialise();
	
	$("#search_button").click(function() {
		codeAddress();
	});
	
	function initialise() {
	
		address  = document.getElementById("address");
		geocoder = new google.maps.Geocoder();
	
		var mapOptions = {
			zoom: 4,
			center: new google.maps.LatLng(52.954783, -1.158109),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		
		map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		
		// Places
		places = new google.maps.places.Autocomplete(address);
		places.bindTo('bounds', map);
		
		google.maps.event.addListener(places, 'places_changed', function() {
			
			var place = places.getPlace();
			
			if (place.geometry.viewport) {
				
				map.fitBounds(place.geometry.viewport);
				
			} else {
			
				map.setCenter(place.geometry.location);
				map.setZoom(17);
			}
		});
		
	}
	
	function codeAddress() {
		
		address = document.getElementById("address").value;
		
	  geocoder.geocode({'address': address}, function(results, status) {
	  
	    if (status == google.maps.GeocoderStatus.OK) {
	    
	      map.setCenter(results[0].geometry.location);
	      map.setZoom(14);
	      
	      // Set position of marker or create a new marker if it doesn't exist.
		    if (marker) {
		    
		    	marker.setPosition(results[0].geometry.location);
		    	
		    } else {
			    
			    marker = new google.maps.Marker({
						map: map,
						position: results[0].geometry.location
					});
				} // if-else
				
				marker.setDraggable(true);
	      
	      if (info_window)
					info_window.close();
			
				if (status == google.maps.GeocoderStatus.OK) {
				
					if (results[0].geometry) {
						
						latitude  = results[0].geometry.location.lat();
						longitude = results[0].geometry.location.lng();
						
						//console.log("latitude: " + latitude);
						//console.log("longitude: " + longitude);
						
						$("#location_header").html("Ghost location:");
						//$("#location_area").show();
						
						if (latitude) {
							
							$("#latitude").html(latitude.toFixed(6));
							$("#Ghost_latitude").val(latitude.toFixed(6));
						}
						
						if (longitude) {
						
							$("#longitude").html(longitude.toFixed(6));
							$("#Ghost_longitude").val(longitude.toFixed(6));
						}
						
					}
					
					info_window = new google.maps.InfoWindow({
						content: "<div>This address is an approximation.<br><br>You can drag this marker to your exact location.</div>"
					});
					
				} else {
				
					info_window = new google.maps.InfoWindow({
						content: "<div>The address you have entered is not clear enough.<br><br>Please drag this marker to your exact location.</div>"
					});
					
					$("#latitude").html('0.000000');
					$("#Ghost_latitude").val('0.000000');
					
					$("#longitude").html('0.000000');
					$("#Ghost_longitude").val('0.000000');
					
				} // if-else
				
				info_window.open(map, marker);
				
				google.maps.event.addListener(marker, "dragstart", function() {
				
					//if (info_window)
						//info_window.close();
				
					$("#location_header").html("Dragging...");
					
					$("#latitude").html('');
					$("#Ghost_latitude").val();
					
					$("#longitude").html('');
					$("#Ghost_longitude").val();
				});
				
				google.maps.event.addListener(marker, "dragend", function() {
				
					$("#location_header").html("Ghost location:");
				
					// Geocode the marker location after dragging
					geocoder.geocode({"latLng": marker.getPosition()}, function(results2, status2) {
					
						if (status2 == google.maps.GeocoderStatus.OK) {
						
							if (results2[0].geometry) {
								
								info_window.setContent("<div>This address is an approximation.<br><br>You can drag this marker to your exact location.</div>");
								
								latitude  = results2[0].geometry.location.lat();
								longitude = results2[0].geometry.location.lng();
								
								if (latitude) {
							
									$("#latitude").html(latitude.toFixed(6));
									$("#Ghost_latitude").val(latitude.toFixed(6));
								}
								
								if (longitude) {
								
									$("#longitude").html(longitude.toFixed(6));
									$("#Ghost_longitude").val(longitude.toFixed(6));
								}
							}
						} else {
						
							info_window.setContent("<div>The address you have entered is not clear enough.<br><br>Please drag this marker to your exact location.</div>");
							
							$("#latitude").html('0.000000');
							$("#Ghost_latitude").val('0.000000');
							
							$("#longitude").html('0.000000');
							$("#Ghost_longitude").val('0.000000');
						}
					});
				});
	      
	    } else {
	      alert("Could not find the search location! Please enter a valid search location.");
	    } // if-else
	  });
	}
});