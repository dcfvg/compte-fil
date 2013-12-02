<!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body>
    <?php
    
    $images = glob("assets/result/source/png-500/*.png");    
    shuffle($images);
    
    $d = 20;
    
    foreach ( $images as $key => $value) {
      echo '<p><img width="120" style="margin:'.rand(-$d,$d).'px '.rand(-$d,$d).'px 0 0" src="'.$value.'" alt=""></p>';
    } ?>
  </body>
</html>