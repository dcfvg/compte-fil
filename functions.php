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
function gen_contact($sets_path, $id_cache_path, $set_id, $start){
  
  $f_sets =  list_files($sets_path);
  $ids = unserialize(file_get_contents($id_cache_path));
  //$ids = gen_unique_ids(1);
  
  foreach ($f_sets[$set_id] as $id_file => $file) {  
    $param = array(
      'code' => "$ids[$id_file]",
      'codetype' => "code128",
      'human_version' => false
    );
    
    copy($file,dirname($file)."/_sd/$ids[$id_file]_".basename($file));
    
    $html .= '
    <p style="background-image:url('.dirname($file).'/_tmb/'.basename($file).')">
      <img class="code" src="barcode.php?'.http_build_query($param).'" >
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
   $results[$key] = implode("",$results[$key]);
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