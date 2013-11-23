<?php
include("functions.php");


$path =  "assets/sets/";
$f_sets =  list_files($path);    

?>
<li class="slide" style="background-image:url(<?php echo $f_sets[0][$_POST["s"]] ?>);"></li>