<?php
include("functions.php");
date_default_timezone_set('Europe/Paris');

$path =  "assets/sets/";
$f_sets =  list_files($path);    

$f = $f_sets[0][$_POST["code"]-1];

if($f == "") echo 'none';
else echo $f;

?>