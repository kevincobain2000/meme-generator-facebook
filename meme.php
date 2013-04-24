<?php
/*
 * Genrates the memes and 
 * Splits the text in Half
 * 
 * PHP Version 5.4 or above
 * 
 *
 *
 * @category Meme Generator
 * @package -
 * @author Pulkit Kathuria
 */


function split_half($string, $center = 0.4) {
  $length2 = strlen($string) * $center;
  $tmp = explode(' ', $string);
  $index = 0; 
  $result = Array(0 => '', 1 => '');
  foreach($tmp as $word) {
    if(!$index && strlen($result[0]) > $length2) $index++;
    $result[$index] .= $word.' ';
  }
  return $result;
}

//this function outlines our text so that it can be seen on any color backdrop
function imagettftextoutline(&$im,$size,$angle,$x,$y,&$col,$fontfile,$text,$width,&$outlinecol) {
  // For every X pixel to the left and the right
  $xd=0-abs($width);
  for ($xc=$x-abs($width);$xc<=$x+abs($width);$xc++) {
    // For every Y pixel to the top and the bottom
    $yd=0-abs($width);
    for ($yc=$y-abs($width);$yc<=$y+abs($width);$yc++) {
      //If this y x combo is within the bounds of a circle with a radius of $width
      //($xc*$xc + $yc*$yc) <= ($width * $width)+999
      if(($xd*$xd+$yd*$yd)<=$width*$width){
	// Draw the text in the outline color
	$text1 = imagettftext($im,$size,$angle,$xc,$yc,$outlinecol,$fontfile,$text);
      }
      $yd++;
    }
    $xd++;
  }
  // Draw the main text
  $text2 = imagettftext($im,$size,$angle,$x,$y,$col,$fontfile,$text);
}	
?>