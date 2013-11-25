<?php
include("functions.php");
include 'tools/krumo/class.krumo.php';

$path =  "assets/sets/";
$code = str_sort($_POST["code"]);


$fs = glob("$path*/_sd/$code*");

// krumo($fs[0]);

echo $fs[0];

?>