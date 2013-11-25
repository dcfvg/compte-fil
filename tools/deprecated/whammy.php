
<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <title></title>
  <script src="../../js/whammy/whammy.js"></script>
  
</head>
<body>
  

  <progress id="progress" style="visibility: hidden;"></progress><br/>

  <input class="uiButton" id="startButton" value="Start" type="button" onclick="startCapture();" />

  <input class="uiButton" id="stopButton" value="Stop" type="button" onclick="stopCapture();" disabled="disabled" /><br />

  <video width="320" height="240" id="campreview" autoplay="true"></video>

  <video width="320" height="240" id="result" controls="controls"></video>
  
  <a id="download" download="now.webm" href="">Download WebM</a>

<div id="information" style="display: inline">Finished</div>

<script type="text/javascript" >
  
  window.URL = window.URL || window.webkitURL;
  navigator.getUserMedia  = navigator.getUserMedia || navigator.webkitGetUserMedia ||
                            navigator.mozGetUserMedia || navigator.msGetUserMedia;

  window.requestAnimationFrame = (function(){
        return  window.requestAnimationFrame       || 
                window.webkitRequestAnimationFrame || 
                window.mozRequestAnimationFrame    || 
                window.oRequestAnimationFrame      || 
                window.msRequestAnimationFrame
      })();

  var video;
  var width;
  var height;
  var canvas;
  var images = [];
  var ctx;
  var result;
  var capture;
  var loopnum;
  var startTime;
  var capturing = false;
  var msgdiv;
  var progress;
  var startButton;
  var stopButton;

  window.onload = function() {

      init();
      var onFailSoHard = function(e) {
          console.log('Rejected!', e);
        };

      if (navigator.getUserMedia) {
          navigator.getUserMedia({
              audio : false,
              video : true
          }, function(stream) {
              video.src = window.URL.createObjectURL(stream);
          }, onFailSoHard);
      } else {
      }
  };

  /**
   * Set the HTML elements we need.
   */
  function init() {
      video = document.getElementById('campreview');
      canvas = document.createElement('canvas');
      ctx = canvas.getContext('2d');
      result = document.getElementById('result');
      msgdiv = document.getElementById('information');
      progress = document.getElementById('progress');
      startButton = document.getElementById('startButton');
      stopButton = document.getElementById('stopButton');
      download = document.getElementById('download');
  }
  /**
   * Capture the next frame of the video.
   */
  function nextFrame(){
      if(capturing){
          var imageData;
          ctx.drawImage(video, 0, 0, width, height);
          imageData = ctx.getImageData(0, 0, width, height);
          images.push({duration : new Date().getTime() - startTime, datas : imageData});
          startTime = new Date().getTime();
          requestAnimationFrame(nextFrame);
      }else{
          requestAnimationFrame(finalizeVideo);
      }

  }

  /**
   * Start the encoding of the captured frames.
   */
  function finalizeVideo(){
      var capture = new Whammy.Video();
      setMessage('Encoding video...');
      progress.max = images.length;
      showProgress(true);
      encodeVideo(capture, 0);
  }

  /**
   * Encode the captured frames.
   * 
   * @param capture
   * @param currentImage
   */
  function encodeVideo(capture, currentImage) {
      if (currentImage < images.length) {
          ctx.putImageData(images[currentImage].datas, 0, 0);
          capture.add(ctx, images[currentImage].duration);
          delete images[currentImage];
          progress.value = currentImage;
          currentImage++;
          setTimeout(function() {encodeVideo(capture, currentImage);}, 5);
      } else {
          var output = capture.compile();
          result.src = window.URL.createObjectURL(output);
          download.href = window.URL.createObjectURL(output);
          setMessage('Finished');
          images = [];
          enableStartButton(true);
      }
  }

  /**
   * Initialize the canvas' size with the video's size.
   */
  function initSize() {
      width = video.clientWidth;
      height = video.clientHeight;
      canvas.width = width;
      canvas.height = height;
  }

  /**
   * Initialize the css style of the buttons and the progress bar
   * when capturing.
   */
  function initStyle() {
      setMessage('Capturing...');
      showProgress(false);
      enableStartButton(false);
      enableStopButton(true);
  }

  /**
   * Start the video capture.
   */
  function startCapture() {
      initSize();
      initStyle();
      capturing = true;
      startTime = new Date().getTime();
      nextFrame();
  }

  /**
   * Stop the video capture.
   */
  function stopCapture() {
      capturing = false;
      enableStopButton(false);
  }

  /* *************************************************************
   *                   Styles functions
   * *************************************************************/

  /**
   * Enable/Disable the start button.
   */
  function enableStartButton(enabled) {
      startButton.disabled = !enabled;
  }

  /**
   * Enable/Disable the stop button.
   */
  function enableStopButton(enabled) {
      stopButton.disabled = !enabled;
  }

  /**
   * Display/Hide the progress bar.
   */
  function showProgress(show) {
      progress.style.visibility = show ? 'visible' : 'hidden';
  }

  /**
   * Display a message in the msgdiv block.
   */
  function setMessage(message) {
      msgdiv.innerHTML = message;
  }

  
</script>

</body>


</html>