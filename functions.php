<?php 
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

?>