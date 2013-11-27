$(function() {
  
  // barcode procesing
  function get_reader_entry(){
    
    var code = $form.find( "input[name='s']" ).val();
    console.log('scanned code :: '+code);
    
    if(code.length < 2) shortcut(code);
    else get_file(code);
     
  }
  function get_file(code){
    var posting = $.post(ajax_url, { code: code } );
    
    posting.done(function( data ) {
      new_slide(data); // TODO : return only data the create slide
    });
  }
  function new_slide(content,css_id){
    css_id = css_id || t();
    
    if(content != "") {
      $( "#my_camera" ).removeClass( "on" );
      $( "#result" ).prepend('<li id="'+css_id+'" class="slide" style="background-image:url('+ content +');">'+get_time()+'</li>');      
    }
  }
    
  // video captures
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
  function toogle_camera(){
    $( "#my_camera" ).toggleClass( "on" );
  }
  function snapshot(){
		// take snapshot and get image data
		
		var canvas = document.createElement('canvas');
		var video = document.getElementById('my_camera');
		
		canvas.width = vid_w;
		canvas.height = vid_h;

		var context = canvas.getContext('2d');
		
		context.drawImage(video, 0, 0, vid_w, vid_h);
    show_clock(context);
	  
	  var stamp = t();
	  var path = "snap/"+stamp+".jpg";
	  var data = canvas.toDataURL('image/jpeg', 1.0 );
	  
	  new_slide(data, stamp);
	  
	  $.ajax({
	    type: "POST",
	    url: "call.php", 
      data: {imgBase64: canvas.toDataURL('image/jpeg', 1.0 ), path:path}
    }).done(function(image) {
       $("#"+stamp).css( "background-image", "url("+image+")" );
    });
    return canvas.toDataURL('image/jpeg', 1.0 );
	}
  function show_clock(context,x,y){
    var x = typeof x !== 'undefined' ? x : '100';
    var y = typeof y !== 'undefined' ? y : '100';
    
    context.fillStyle = "white";
    context.font = "bold 18px Monaco";
    context.fillText(get_time(), x, y);
  }
  
  // user interface
  function shortcut(code){
    
    console.log("shortcut :: "+code);
    
    switch (code.toLowerCase()){
      case "c": toogle_camera();            break;
      case "s": snapshot();                 break;
      case "g": gridMode();                 break;
      case "h": stackMode();                break;
      
      default:console.log("shortcut :: no match");
    }
  }
  function scanmode(){
    // s field ready
    $("#code_input").focus();
    $("#code_input").val('');
  }
  function gridMode(){
    // fs images to tumbnails
    $( "body" ).toggleClass( "mini" );
    $( "#my_camera" ).removeClass( "on" );
    scanmode();
  }
  function stackMode(){
    $( "body" ).toggleClass( "stack" );
    $( "#my_camera" ).removeClass( "on" );
    
    refreshZindex();
    scanmode();
  }
  
  function refreshZindex(){
    $("#result li").each(function(n) {
      var pos = 1 + Math.floor(Math.random() * 10);
      margin_rand = pos+'% '+pos+'% '+pos+'% '+pos+'%';
      
      $(this).css('z-index', 1000-n).css('margin',margin_rand);
    });
  }
  
  // utils
  function get_time(){
    var date=new Date();
		return str_pad(date.getHours(),2)+":"+str_pad(date.getMinutes(),2)+":"+str_pad(date.getSeconds(),2);
  }
  function t(){
    return new Date().getTime();
  }
  function str_pad(n, p, c) {
    var pad_char = typeof c !== 'undefined' ? c : '0';
    var pad = new Array(1 + p).join(pad_char);
    return (pad + n).slice(-pad.length);
  }
  function gen_content(){
    
    var a = ["AAv", "ABg", "AAE", "AAe", "AAf", "AAF"];
    a.forEach(function(entry) {get_file(entry);});
   
  }

  // init
  function init(){
    init_camera();
    gen_content();
    scanmode();
  }
  
  // vars
  var vid_h=1080,vid_w=1920,
  $video = $( "#my_camera" ),
  $form = $( "#searchForm" ),
  ajax_url = $form.attr( "action" );
  mode="normal"
  
  // events
  $form.submit(function( event ) {
    event.preventDefault();
    get_reader_entry();
    scanmode();
  });
  $( "html" ).keypress(function(event) {$("#code_input").focus();});
  
  init();

})