<?php
include("functions.php");
date_default_timezone_set('Europe/Paris');

$path =  "assets/sets/";
$f_sets =  list_files($path);    

$img = $f_sets[0][$_POST["s"]-1];

if($img == "") echo 'none';
else echo '<li class="slide" style="background-image:url('.$img.');">'.date('h:i:s').'</li>';
?>
