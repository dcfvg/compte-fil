<?php
include 'krumo/class.krumo.php'; // debug only
include "../functions.php";

$sets_path      = "..".$GLOBALS['sets_path'];
$id_cache_path  = "../".$GLOBALS['id_cache_path'];
$set_id         = 0;
$start          = 100;

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $_GET['set_name'] ?></title>
    <link rel="stylesheet" href="css/screen.css">
  </head>
  <body>
    <ol id="sets" class="no-print">
      <?php
    echo '<li>
    <a href="?set_name=all">[print]</a> all
    </li>';
      foreach (glob($sets_path.'/*/') as $set_id => $dir) {
        echo '<li>
        <a href="?set_name='.basename($dir).'&refresh=ok">[refresh]</a>
        <a href="?set_name='.basename($dir).'">[print]</a>
        '.basename($dir).' ('.count(glob($dir.'/jpg-5000/*.*')).')
        </li>';
      }
      ?>
    </ol>
    <h1><?php echo $_GET['set_name'] ?></h1>
    <div class="sheet">
      <?php
        if(isset($_GET['set_name'])) {
            $set_name = $_GET['set_name'];
            if(isset($_GET['refresh'])) echo gen_ids($set_name);
            else echo gen_contact($set_name, 500);
        }
				if($_GET['set_name'] == "all"){
          echo $sets_path;
					foreach (glob($sets_path.'/*/') as $set_id => $dir) {
						echo '<h1>'.basename($dir).'</h1>';
						echo gen_contact(basename($dir), 500);
		      }
				}
      ?>
    </div>
  </body>
</html>