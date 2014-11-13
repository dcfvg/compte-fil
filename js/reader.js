$(function() {
  
  // barcode procesing
  function get_reader_entry(){
    var code = $form.find( "input[name='s']" ).val();
    console.log('scanned code :: '+code);
    
    if(code.length < 2) shortcut(code);
    else if(code != prev_code) get_file(code);
    prev_code = code;
    console.log(prev_code);
  }
  function get_file(code){
    var posting = $.post(ajax_url, { code: code } );
    
    posting.done(function( data ) {
      new_slide(data); // TODO : return only data the create slide
			console.log(data);
    });
  }
  function new_slide(content, css_id){
    css_id = css_id || t();
    
    var margin_rand = 'rdmargin="'+randomInt(-15,15)+'%'+'"',   
    re = /(?:\.([^.]+))?$/,
    ext = re.exec(content.toLowerCase())[1];
    
    if(content != "") {
      $video.removeClass( "onAir" );
      $stack.removeClass( "onAir" );
      
      pauseAllVideo();
      
      if(ext != "mov") $stack.prepend('<li id="'+css_id+'" class="slide tjpg" '+margin_rand+' style="z-index:'+($stackSildes.length*100)+';background-image:url('+ content +');"><span>'+get_time()+'</span></li>')
      else $stack.prepend('<li id="'+css_id+'" class="slide tmov" '+margin_rand+' style="z-index:'+($stackSildes.length*100)+';"><video id="vid'+css_id+'" width="100%" height="100%" class="docvideo" autoplay controls><source src="'+content+'" type="video/mp4"></video></li>');
    }
    gotolastSlide();
  }    
  // video captures
  function init_camera(){

    // navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    navigator.getUserMedia = ( navigator.getUserMedia ||
                               navigator.webkitGetUserMedia ||
                               navigator.mozGetUserMedia ||
                               navigator.msGetUserMedia);

    window.URL = window.URL || window.webkitURL || window.mozURL || window.msURL;

    if (navigator.getUserMedia && window.URL) {
      var video = document.getElementById('my_camera');

      navigator.getUserMedia(
        // constraints
        {
          "audio": false,
          "video": { "mandatory": { "minWidth": vid_h, "minHeight": vid_h } }
        },
        // successCallback
        function(stream) { // got access, attach stream to video
          video.src = window.URL.createObjectURL( stream ) || stream;
        },
        // errorCallback
        function(err) {
           console.log("The following error occured: " + err);
        }
      );
    }
    else {
      alert("getUserMedia not supported on your machine!");
    }
  }
  function toogle_camera(){
    gotolastSlide();
    $video.toggleClass( "onAir" );
    $stack.toggleClass( "onAir" );
  }
  function toogle_camera_180(){
    $video.toggleClass( "isight" );
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
  function pauseAllVideo(){
    $('.docvideo').each(function() {
        $(this).get(0).pause();
    });
  }
  // user interface
  function shortcut(code){
    console.log("shortcut :: "+code);
    switch (code.toLowerCase()){
      case "c": toogle_camera();            break;
      case "i": toogle_camera_180();        break;
      case "s": snapshot();                 break;
      
      case "g": gridMode();                 break;
      case "h": stackMode();                break;
      
      case "p": prevSlide();                break;
      case "o": nextSlide();                break;
      
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
    $video.removeClass( "onAir" );
    scanmode();
    gotoSlide();
    
  }
  function stackMode(){
    $( "body" ).toggleClass( "stack" );
    $video.removeClass( "onAir" );
    
    $stkElmt.each(function(n) {
      $(this).css('margin',$(this).attr("rdmargin"));
    });
    
    scanmode();
  }

  function refreshZindex(){
    $stackSildes.each(function(n) {
      var pos = 1 + Math.floor(Math.random() * 10);
      margin_rand = pos+'% '+pos+'% '+pos+'% '+pos+'%';
      
      $(this).css('z-index', 1000-n).css('margin',margin_rand);
    });
  }
  function prevSlide(){
    curSlideId ++;
    gotoSlide();
  }
  function nextSlide(){
    curSlideId --;
    gotoSlide();
  }
  function gotoSlide(){    
    if(curSlideId > $stack.children().length -1 ) curSlideId = 0;
    if(curSlideId < 0) curSlideId = $stack.children().length -1;
    
    console.log(curSlideId + " / " + $stack.children().length);
    
    $curSlide = $stack.find('li:nth-child('+(curSlideId+1)+')');
    
    $(".on").removeClass("on");
    $curSlide.addClass( "on" );

    $("body").scrollTo($curSlide, 200 );
  }
  function gotolastSlide(){
    $("body").scrollTo( $stack.find('li:first-child()'), 600 );
		curSlideId = 0;
		gotoSlide();
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
    var a = ["AAv", "ABg", "AAE", "AAe", "AAf", "AAF","ABv", "AAg", "ABE", "ABe", "ABf", "ABF"];
    a.forEach(function(entry) {get_file(entry);});
  }
  function randomInt (min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
  }
  // init
  function init(){
    init_camera();
    //gen_content();
    scanmode();
  }
  
  // vars
  var vid_h=1080,vid_w=1920, 
  prev_code, curSlideId = 0,
  $curSlide,
  $video = $( "#my_camera" ),
  $form = $( "#searchForm" ),
  $stack = $('#result'),
  $stackSildes = $stack.find('li'),
  ajax_url = $form.attr("action");
  
  // events
  $form.submit(function( event ) {
    event.preventDefault();
    get_reader_entry();
    scanmode();
  });
  $( "html" ).keypress(function(event) {$("#code_input").focus();});
  
  init();
})