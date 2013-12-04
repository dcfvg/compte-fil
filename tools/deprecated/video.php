<!DOCTYPE html>
<html>
<body>
<video width="100%" height="100%" id="vid" controls>
  <source src="MVI_5177.MOV" type="video/mp4">
</video>

</body>
<script type="text/javascript" charset="utf-8">
  (function(){
    document.getElementById('vid').play();
    setTimeout(function(){
        document.getElementById('vid').pause();
     }, 5000);
  })();
</script>
</html>