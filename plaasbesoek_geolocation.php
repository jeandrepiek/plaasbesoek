<!DOCTYPE html>
<html>
  <head>
    <title>Plaasbesoek</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link href="font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
    <style>
      html, body {
        height: 100%;
        margin: 0px;
        padding: 0px;
		font-family: 'Ubuntu', sans-serif;
		font-size: 14px;
      }
	  #map-canvas{
		height: 100%;
        margin-top: 50px;
        padding: 0px;
	  }
	  #nav{
		  position:fixed;
		  top:0;
		  width:100%;
		  height:50px;
		  background:rgba(204,204,204,1);
		  z-index:999;
		  box-shadow:1px 2px 1px #999999;
	  }
	  #nav img{
		  height:95%;
		  margin-left:20%;
	  }
	  #nav i{
		  float:right;
		  margin-right:20%;
		  height: 100%;
		  display: flex;
		  align-items: center;
	  }
	  #tripmeter{
		position: absolute;
		left: 15px;
		bottom: 0;
	  }
	  .icons{
		background: rgb(255, 255, 255) none repeat scroll 0% 0%;
		border: 1px solid #999;
		border-radius: 11px;
		padding: 5px;
	  }
	  .cords{
		  display:inline;
	  }
    </style>
    
  </head>
  <body>
  	<div id="nav"><img src="../img/mgk_logo_remastered.png"/><i id="add" class="fa fa-plus-square fa-2x"></i></div>
    <div id="map-canvas"></div>
    <div id="tripmeter">
      <!--<p>
        Starting Location (lat, lon):<br/>
        <span id="startLat">???</span>°, <span id="startLon">???</span>°
      </p>-->
      <p>
        <div class="icons">
        	<i class="fa fa-globe"></i>
            <div class="cords">
            	<span id="currentLat"></span>°, <span id="currentLon"></span>°
            </div>
        </div>
      </p>
      <p>
      	<div class="icons">
        	<i class="fa fa-crosshairs"></i>
        	<div class="cords">
        		<span id="accuracy">0</span> m
        	</div>
     	</div>
      </p>
      <p>
      	<div class="icons">
        	<i class="fa fa-meanpath"></i>
        	<div class="cords">
        		<span id="distance">0</span> km
        	</div>
     	</div>
      </p>
    </div>
    
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    
	<script type="text/javascript">
	
	if (navigator.geolocation) {
		console.log('Geolocation is supported!');
	}
	else {
		alert('Geolocation is not supported for this Browser/OS version yet.');
	}
	
	var map;
	
	function initMap() {
		
		var mapOptions = {
			zoom: 15
		};
		
		map = new google.maps.Map(document.getElementById('map-canvas'),
		mapOptions);
		
		initWindow();
	}
	
	function initWindow(){
		
		var startPos;
		
		navigator.geolocation.getCurrentPosition(function(position) {
			
			startPos = position;
			var pos = new google.maps.LatLng(position.coords.latitude,
											 position.coords.longitude);
		
			var infowindow = new google.maps.InfoWindow({
				map: map,
				position: pos,
				content: '<div style="line-height:1.8">Posisie gevind.</div>'
			});
			
			map.setCenter(pos);
			
			document.getElementById('currentLat').innerHTML = startPos.coords.latitude;
			document.getElementById('currentLon').innerHTML = startPos.coords.longitude;
			document.getElementById('accuracy').innerHTML = startPos.coords.accuracy;
			
			console.log(position);
		}, 
		function(error) {
			alert('Error occurred. Error code: ' + error.code);
			// error.code can be:
			//   0: unknown error
			//   1: permission denied
			//   2: position unavailable (error response from locaton provider)
			//   3: timed out
		});
		
		navigator.geolocation.watchPosition(function(position) {
			document.getElementById('currentLat').innerHTML = position.coords.latitude;
			document.getElementById('currentLon').innerHTML = position.coords.longitude;
			
			document.getElementById('distance').innerHTML =
			calculateDistance(startPos.coords.latitude, startPos.coords.longitude,
							  position.coords.latitude, position.coords.longitude);
		});
		
		
		//if(navigator.geolocation) {
//			navigator.geolocation.getCurrentPosition(function(position) {
//			var pos = new google.maps.LatLng(position.coords.latitude,
//											 position.coords.longitude);
//		
//			var infowindow = new google.maps.InfoWindow({
//				map: map,
//				position: pos,
//				content: '<div style="line-height:1.8">Posisie gevind.</div>'
//			});
//		
//		map.setCenter(pos);
//		}, function() {
//			handleNoGeolocation(true);
//		});
//		} 
//		else {
//			handleNoGeolocation(false);
//		}
	}
	
	//function handleNoGeolocation(errorFlag) {
//		if (errorFlag) {
//			var content = 'Error: The Geolocation service failed.';
//		} else {
//			var content = 'Error: Your browser doesn\'t support geolocation.';
//		}
//		
//		var options = {
//			map: map,
//			position: new google.maps.LatLng(60, 105),
//			content: content
//		};
//		
//		var infowindow = new google.maps.InfoWindow(options);
//		map.setCenter(options.position);
//	}
	
	google.maps.event.addDomListener(window, 'load', initMap);
	
	//var add = document.getElementById('add');
	
	//add.addEventListener('click',function(event){
//		handleRequest();
//	});
	
	function calculateDistance(lat1, lon1, lat2, lon2) {
	  var R = 6371; // km
	  var dLat = (lat2 - lat1).toRad();
	  var dLon = (lon2 - lon1).toRad(); 
	  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
			  Math.cos(lat1.toRad()) * Math.cos(lat2.toRad()) * 
			  Math.sin(dLon / 2) * Math.sin(dLon / 2); 
	  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)); 
	  var d = R * c;
	  return d;
	}
	Number.prototype.toRad = function() {
	  return this * Math.PI / 180;
	}
    </script>    
  </body>

</html>