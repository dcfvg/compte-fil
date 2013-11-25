<?php
include 'krumo/class.krumo.php'; // debug only
include "../functions.php";

$sets_path      = "../".$GLOBALS['sets_path'];
$id_cache_path  = "../".$GLOBALS['id_cache_path'];
$set_id         = 0;
$start          = 100;

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
    <?php echo gen_contact($sets_path, $id_cache_path, $set_id, $start); ?>
  </div>
  </body>
</html>