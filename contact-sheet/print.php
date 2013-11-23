<?php

// include('../krumo/class.krumo.php'); // debug only

$dir_assets = "../assets/"; // media location
$dir_sets = "sets/";        // image collection ( sets ) location
$code_type = "code128";     // codebar type ( symbologies )

function contact($dir_assets,$dir_sets,$code_type){
  
  $img_sets = glob($dir_assets.$dir_sets."*"); 
  
  foreach ($img_sets as $id_set => $img_set){
   
   $imgs = glob($img_set."/*.*");
   
   foreach ($imgs as $id_img => $img) {
     
      $i++;
      $code = str_pad( $i, 2, "0", STR_PAD_LEFT); // barcode 
      
      $html .= '
      <p>
        <img class="pic" src="'.$img.'" alt="">
        <img class="code" src="barcode.php?text='.$code.'&codetype='.$code_type.'" alt="">
      </p>';
   }
  }
  return $html;
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/screen.css">
  </head>
  <body>
  <div class="sheet">
    <?php contact($dir_assets,$dir_sets,$code_type);  ?>
  </div>
  </body>
</html>