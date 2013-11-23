<?php
//include('../krumo/class.krumo.php'); // debug only
include("../functions.php");

$path =  "../assets/sets/";
$f_sets =  list_files($path);

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
    <?php echo gen_contact($f_sets,0);  ?>
  </div>
  </body>
</html>