<?php
include("functions.php");
include 'tools/krumo/class.krumo.php';

date_default_timezone_set('Europe/Paris');

$path =  "assets/sets/";
$code = str_sort($_POST["code"]);


$fs = glob("$path*/_sd/$code*");

// krumo($fs[0]);

echo $fs[0];

?>