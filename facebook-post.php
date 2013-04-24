<?php
/*
 * Uses the facebook Graph API
 * 
 * 
 * PHP Version 5.4 or above
 * 
 *
 *
 * @category Posting on FaceBook
 * @package -
 * @author Pulkit Kathuria
 */

//session_start();
require_once("facebook-php-sdk/src/facebook.php");
require_once("../../fbconfig/config.php"); //$config; @type=array [appid] & [secret]


$facebook = new Facebook($config);

$user = $facebook->getUser();

//$facebook->destroySession();
//exit;

if (!$user) {
    header("Location: {$facebook->getLoginUrl(array("scope" => publish_stream, status_update))}");
    exit;
  }

//$myProfile = $facebook->api('/me');

if (isset($_GET['image']) && $_GET['image'] != ""){
	$picUrl = 'savedimages/'.$_GET['image'];//'http://somedomain.com/picture.jpg';
	$photoId = $facebook->api("me/photos","POST",array('url'=>$picUrl,'message'=>"lol"));
	header("Location:index.php?m=1");
	
	exit;
}
else header("Location:index.php");


?>