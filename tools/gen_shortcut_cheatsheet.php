<?php
include 'krumo/class.krumo.php'; // debug only
include "../functions.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/screen.css">
  </head>
  <body id="shortcut">
    <div class="sheet">
      <?php
        $shortcut = array(
          "c" => "camera on/off", 
          "s" => "camera capture", 
          "g" => "mode grille",
          "p" => "en arrière",
          "o" => "en avant",
        ); 
        foreach ($shortcut as $key => $label) {
          $param = array(
            'code' => $key,
            'codetype' => "code128",
            'human_version' => false,
            'size' => "55"
          );
          echo '
          <p>
            <span class="code"style="background-image:url(barcode.php?'.http_build_query($param).')"></span>
            '.$label.'
          </p>';
        }
      ?>
    </div>
  </body>
</html>