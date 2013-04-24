<?php
/*
 * index.php the main interface of the app
 * Displays app.html which contains the form etc..
 * Saves the file the dir ./savedimages/
 * PHP Version 5.4 or above
 * 
 *
 *
 * @category Fun
 * @package -
 * @author Pulkit Kathuria
 */

//Set error reporting to 0
//error_reporting(0);
//Start the OB Buffer
ob_start();

include_once("../../libs/MySmarty.class.php");
include_once("meme.php");

session_start();
session_regenerate_id(true);
$smarty = new MySmarty;

$error_messages=array();
$image_results = NULL;
$memed_images_local = array();

function generate_random_string($name_length = 8) {
	$alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	return substr(str_shuffle($alpha_numeric), 0, $name_length);
}
function get_memes(){
	if(isset($_POST['topmeme']) && $_POST['topmeme'] != "") $topmeme=$_POST['topmeme'];
	else $topmeme = "Leave me";//TOP_TEXT;
	if(isset($_POST['bottommeme']) && $_POST['bottommeme'] != "") $bottommeme=$_POST['bottommeme'];
	else $bottommeme = "Alone";//TOP_TEXT;
	return array($topmeme, $bottommeme);

}
if(isset($_POST['q'])){
	if($_POST['q'] == "") $q = urlencode("Grumpy Cat");
 	else $q = urlencode($_POST['q']);
 	$jsonurl = "https://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=".$q;
 	$image_results = json_decode(file_get_contents($jsonurl), true);

	foreach($image_results['responseData']['results'] as $counter=>$result){

	      $file = $result['unescapedUrl'];
		
	      //$text1 = "I Said Lunch";//TOP_TEXT;
        //$text2 = "Not LAUNCH";//BOTTOM_TEXT;
	      $memes_text = get_memes();
	      $text1 = $memes_text[0];
	      $text2 = $memes_text[1];

        $font = "templates/fonts/IMPACT.TTF"; //PATH_TO_FONT;
	
        $im = imagecreatefromjpeg($file);
        $black = imagecolorallocate($im, 0x00, 0x00, 0x00);
        $white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
        $text1 = strtoupper($text1);
        $text2 = strtoupper($text2);
        
        if(strlen($text1) > 30)
        {
            $result = split_half($text1);
            if($result[0])
            {
                $top_font = 45;
                $bbox = imageftbbox($top_font, 0, $font, $result[0]);
                if($bbox[4] > 450)
                {
                    $top_font = ($top_font * 450) / $bbox[4];
                    $bbox = imageftbbox($top_font, 0, $font, $result[0]);
                }
                $x = (500 - $bbox[4]) / 2;  //center the font.
                imagettftextoutline($im, $top_font, 0, $x, ($top_font + 15), $white, $font, $result[0],2,$black);
            }
            if($result[1])
            {
                $top_font2 = 45;
                $bbox = imageftbbox($top_font2, 0, $font, $result[1]);
                if($bbox[4] > 450)
                {
                    $top_font2 = ($top_font2 * 450) / $bbox[4];
                    $bbox = imageftbbox($top_font2, 0, $font, $result[1]);
                }
                $x = (500 - $bbox[4]) / 2;
                imagettftextoutline($im, $top_font2, 0, $x, ($top_font + $top_font2 + 30), $white, $font, $result[1],2,$black);
            }
            
        }
        else
        {
            $top_font = 45;
            $bbox = imageftbbox($top_font, 0, $font, $text1);
            if($bbox[4] > 450)
            {
                $top_font = ($top_font * 450) / $bbox[4];
                $bbox = imageftbbox($top_font, 0, $font, $text1);
            }
            $x = (500 - $bbox[4]) / 2;
            imagettftextoutline($im, $top_font, 0, $x, ($top_font + 15), $white, $font, $text1,2,$black);
        }
        
        if(strlen($text2) > 30)
        {
            $result = split_half($text2);
            if($result[1])
            {
                $bottom_font2 = 45;
                $bbox = imageftbbox($bottom_font2, 0, $font, $result[1]);
                //make sure the width of the text is no greater than 450px total;
                if($bbox[4] > 450)
                {
                    $bottom_font2 = ($bottom_font2 * 450) / $bbox[4];
                    $bbox = imageftbbox($bottom_font2, 0, $font, $result[1]);
                }
                $x = (500 - $bbox[4]) / 2;
                $y = imagesy($im) - 25;
                imagettftextoutline($im, $bottom_font2, 0, $x, $y, $white, $font, $result[1],2,$black);
            }
            if($result[0])
            {
                $bottom_font = 45;
                $bbox = imageftbbox($bottom_font, 0, $font, $result[0]);
                if($bbox[4] > 450)
                {
                    $bottom_font = ($bottom_font * 450) / $bbox[4];
                    $bbox = imageftbbox($bottom_font, 0, $font, $result[0]);
                }
                $x = (500 - $bbox[4]) / 2;
                $y = imagesy($im) - 40 - $bottom_font2;
                imagettftextoutline($im, $bottom_font, 0, $x, $y, $white, $font, $result[0],2,$black);
            }    
        }
        else
        {
            $bottom_font = 45;
            $bbox = imageftbbox($bottom_font, 0, $font, $text2);
            if($bbox[4] > 450)
            {
                $bottom_font = ($bottom_font * 450) / $bbox[4];
                $bbox = imageftbbox($bottom_font, 0, $font, $text2);
            }
            $x = (500 - $bbox[4]) / 2;
            $y = imagesy($im) - 25;
            imagettftextoutline($im, $bottom_font, 0, $x, $y, $white, $font, $text2,2,$black);
        }
        
			//This is the random name for the file
	    $random_name = $counter.$counter.generate_random_string();
	    imagejpeg($im, "savedimages/{$random_name}.jpg");
	    $memed_images_local[] = $random_name;
	    //Clean up memory
      imagedestroy($im);
        break;
	
  }

}

header("Cache-Control: max-age=3600, must-revalidate");
$smarty->assign('memed_images_local',$memed_images_local);
$smarty->assign('image_results', $image_results);
$smarty->display('templates/app.html');

?>
