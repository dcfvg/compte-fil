$(function() {
  var vid_h=1080,vid_w=1920;

  // events
  $( "html" ).keypress(function(event) {
    console.log(event.which);
    
    if ( event.which == 103 ) thumbMode();      // g
    if ( event.which == 99 )  toogle_camode();  // c
    if ( event.which == 115 ) take_snapshot();  // s
    
  });
  $( "#searchForm" ).submit(function( event ) {
    event.preventDefault();
    get_media($( this ));
  });
  
  // functions
  function scanmode(){
    // s field ready
    $("#code_input").focus();
    $("#code_input").val('');
  }
  function thumbMode(){
    // fs images to tumbnails
    $( "body" ).toggleClass( "mini" );
  }
  function get_media($form){
    
    var term = $form.find( "input[name='s']" ).val(),
    url = $form.attr( "action" );
    
    console.log('scanned_id :: '+term);
    $(document).attr('title',term ); 
    
    var posting = $.post( url, { s: term } );
    
    posting.done(function( data ) {
      $( "#result" ).prepend( data );
      scanmode();
    });
  }
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
  function toogle_camode(){
    $( "#my_camera" ).toggleClass( "on" );
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
		$( "#result" ).prepend('<li class="slide" style="background-image:url('+data_uri+');"></li>');
	}
  
  scanmode();
  init_camera();
})