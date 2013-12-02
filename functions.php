<?php 

$GLOBALS['id_cache_path'] = "/assets/id_cache.txt";
$GLOBALS['sets_path'] = "/assets/sets/";
date_default_timezone_set('Europe/Paris');

# file read and export
function list_files($path){
  
  // construct sets and files array 
  
  $f_sets = glob($path."*");
  foreach ($f_sets as $id_set => $f_set) $f_sets[$id_set] = glob($f_set."/*.*");

  return $f_sets;
}
function get_resolutions($path){
  foreach (glob($path.'/jpg-*/') as $res_folder) {
   $res[] =  str_replace("jpg-","",basename($res_folder));
  }
  // ksort($res, SORT_NATURAL);
  return $res;
}
function idFromPath($filename){
  $parts = explode("_",preg_replace("/\\.[^.\\s]{3,4}$/", "",basename($filename)));
  return $parts[1];
}
function starFromSetName($set_name){
  $parts = explode("_",preg_replace("/\\.[^.\\s]{3,4}$/", "",basename($set_name)));
  return $parts[0];
}
function gen_ids($set_name){
  
  $id_start = starFromSetName($set_name);
  $set_path = $GLOBALS['sets_path'].$set_name;
  $ids = unserialize(file_get_contents($GLOBALS['id_cache_path']));
  $resolutions = get_resolutions($set_path);
  
  array_map('unlink', glob($set_path."/www-*/*.jpg"));
  
  foreach (glob($set_path."/jpg-5000/*.jpg") as $id_file => $file) {
    $code = $ids[$id_file+$id_start];
    $parent = dirname(dirname($file));
    
    foreach ($resolutions as $id => $res) {
      copy($parent.'/jpg-'.$res.'/'.basename($file),$parent.'/www-'.$res.'/'.str_pad($id_file, 5, "0", STR_PAD_LEFT).'_'.$code.'.jpg');
    } 

    $console .= '
    ( '.($id_file+$id_start).' ) '.$code.' -> '.basename($file);
  }
  return "<pre>
  
  start : $id_start
  
  $console</pre>"; 
}
function gen_contact($set_name,$res){
  $set_path = $GLOBALS['sets_path'].$set_name;
  $elements = glob($set_path.'/www-'.$res.'/*.*');

  foreach ($elements as $id_file => $file) {
    
    $param = array(
      'code' => idFromPath($file),
      'codetype' => "code128",
      'orientation' => "vertical",
      'human_version' => false,
      'size' => "10"
    );
    
    $parent = dirname(dirname($file));
    
    // $grey = 100+($id_file%100);
    // $bg = 'background-color:rgb('."$grey,$grey,$grey".');';
    
    $html .= '
    <p style="background-image:url('.$parent.'/www-'.$res.'/'.basename($file).');'.$bg.'">
      <span class="code"style="background-image:url(barcode.php?'.http_build_query($param).')"></span>
    </p>';
  }
  
  return $html;
}
## barcode ID
function cartesian($input) {
    $result = array();

    while (list($key, $values) = each($input)) {
        // If a sub-array is empty, it doesn't affect the cartesian product
        if (empty($values)) {
            continue;
        }

        // Seeding the product array with the values from the first sub-array
        if (empty($result)) {
            foreach($values as $value) {
                $result[] = array($key => $value);
            }
        }
        else {
            // Second and subsequent input sub-arrays work like this:
            //   1. In each existing array inside $product, add an item with
            //      key == $key and value == first item in input sub-array
            //   2. Then, for each remaining item in current input sub-array,
            //      add a copy of each existing array inside $product with
            //      key == $key and value == first item of input sub-array

            // Store all items to be added to $product here; adding them
            // inside the foreach will result in an infinite loop
            $append = array();

            foreach($result as &$product) {
                // Do step 1 above. array_shift is not the most efficient, but
                // it allows us to iterate over the rest of the items with a
                // simple foreach, making the code short and easy to read.
                $product[$key] = array_shift($values);

                // $product is by reference (that's why the key we added above
                // will appear in the end result), so make a copy of it here
                $copy = $product;

                // Do step 2 above.
                foreach($values as $item) {
                    $copy[$key] = $item;
                    $append[] = $copy;
                }

                // Undo the side effecst of array_shift
                array_unshift($values, $product[$key]);
            }

            // Out of the foreach, we can add to $results now
            $result = array_merge($result, $append);
        }
    }

    return $result;
}
function gen_unique_ids($lenght){
  
  ini_set('memory_limit', '-1');
  set_time_limit(600);
  $chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0,9));
  
  foreach (range(0,$lenght) as $key => $value) $input[$value] = $chars;
    
  $results = cartesian($input);
  foreach ($results as $key => $result) {
   asort($results[$key]);
   $results[$key] = str_sort(implode("",$results[$key]));
  }
  $results = array_unique($results);
  return array_merge($results);
}
function get_id($id,$cache_path){
  $ids = unserialize(file_get_contents("assets/id_cache.txt"));
  //krumo($ids);
  return $ids[$id];
}
function str_sort($string){
  $stringParts = str_split($string);
  sort($stringParts);
  return implode('', $stringParts);
}
?>