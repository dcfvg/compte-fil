<?php
include("functions.php");
date_default_timezone_set('Europe/Paris');

$path =  "assets/sets/";
$f_sets =  list_files($path);    

?>
<li class="slide" style="background-image:url(<?php echo $f_sets[0][$_POST["s"]-1] ?>);"> <?php echo date('h:i:s'); ?></li>