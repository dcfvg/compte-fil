<?php 
# file read and export
function list_files($path){
  
  // construct sets and files array 
  
  $f_sets = glob($path."*");
  foreach ($f_sets as $id_set => $f_set) $f_sets[$id_set] = glob($f_set."/*.*");

  return $f_sets;
}
function gen_contact($f_sets,$set){ 
  
  // create contact sheet html of a specific image set

  $imgs = glob($f_sets[$set]."/*.*");
  $code_type = "code128";        // codebar type ( symbologies )
   
  foreach ($f_sets[$set] as $id_img => $img) {
    $code  = $id_img+1; //str_pad( $id_img, 2, "0", STR_PAD_LEFT); // barcode 
    $html .= '
    <p style="background-image:url('.$img.')">
      <img class="code" src="barcode.php?text='.$code.'&codetype='.$code_type.'" alt="">
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
  $chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0,9));
  
  foreach (range(0,$lenght) as $key => $value) $input[$value] = $chars;
    
  $results = cartesian($input);
  foreach ($results as $key => $result) {
   asort($results[$key]);
   $results[$key] = implode("",$results[$key]);
  }

  return array_unique($results);
}
function get_id($id,$cache_path){
  $ids = unserialize(file_get_contents("assets/id_cache.txt"));
  //krumo($ids);
  return $ids[$id];
}


?>