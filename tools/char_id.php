<?php 
include 'krumo/class.krumo.php';
include '../functions.php';

$cache = "../".$GLOBALS['id_cache_path'];

if(isset($_GET["f5"])){
  $ids = gen_unique_ids($_GET["f5"]);
  file_put_contents($cache, serialize($ids));
}else{
  $ids = unserialize(file_get_contents($cache));
}

krumo(count($ids));
krumo($ids);

?>