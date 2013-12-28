<!doctype html>
<html lang="en">
  <head>
  </head>
  <style type="text/css" media="screen">
  	.isight { 
		-webkit-transform:rotate(180deg);
		-webkit-perspective: 500px;
	}
  	#my_camera {
  		width:100%;
  	}
  </style>
<body>

	<video class="isight" id="my_camera" autoplay="autoplay"></video>
	
	<!-- A button for taking snaps -->
	<form>
		<input type=button value="Take HD Snapshot" onClick="take_snapshot()">
	</form>
	
	<div id="capture">Your captured image will appear here...</div>
	
	
	<script language="JavaScript">
	
	var vid_h=1080,vid_w=1920;
	init_camera();
	
  function init_camera(){
    
		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
		
		window.URL = window.URL || window.webkitURL || window.mozURL || window.msURL;

		if (navigator.getUserMedia && window.URL) {
			var video = document.getElementById('my_camera');

			navigator.getUserMedia({
				"audio": false,
				"video": { "mandatory": { "minWidth": vid_h, "minHeight": vid_h } }
			},
			function(stream) { // got access, attach stream to video
				video.src = window.URL.createObjectURL( stream ) || stream;
			});
		}
		else {
			alert("getUserMedia not supported on your machine!");
		}
  }
	function take_snapshot(){
		// take snapshot and get image data
		var video = document.getElementById('my_camera');
		
		var canvas = document.createElement('canvas');
		canvas.width = vid_w;
		canvas.height = vid_h;
		var context = canvas.getContext('2d');
		
		context.drawImage(video, 0, 0, vid_w, vid_h);
		var data_uri = canvas.toDataURL('image/jpeg', 1.0 );
		
		// display results in page
		document.getElementById('capture').innerHTML = '<img src="'+data_uri+'"/>';
	}
	</script>
	
	
</body>
</html>

