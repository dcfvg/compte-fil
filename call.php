<?php
include("functions.php");
include 'tools/krumo/class.krumo.php';

$assets =  "assets/";

if(isset($_POST["code"])) {
  $code = str_sort($_POST["code"]);
  $fs = glob($assets."sets/*/www-1920/*_$code.*");

  echo $fs[0];
}
if(isset($_POST["imgBase64"]) || isset($_POST["path"])){

  $p = $_POST["path"];
  $rawData = $_POST['imgBase64'];
  $filteredData = explode(',', $rawData);

  $unencoded = base64_decode($filteredData[1]);

  file_put_contents($assets.$p,$unencoded);

  //echo $_POST["imgBase64"];

  echo $assets.$p;
}
?>