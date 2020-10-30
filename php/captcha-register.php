<?php
session_start();

	$img = imagecreate(53,16);
	$bg = imagecolorallocate($img, 255, 142,35);
	$text = imagecolorallocate($img, 255,255,255);
	$randnum = rand(100000,999999);
	imagefill($img, 0, 0, $bg);
	imagestring($img, 80, 0, 0, $randnum, $text);
	//header('Content-type: image/png');
	imagepng($img);
	$_SESSION['realcode'] = ''.$randnum;
?>
