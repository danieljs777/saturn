<?php

/*
 * ******************************************************************************

  Developed by Daniel Jordao Santana (daniel.js@gmail.com)
  Copyright (c) 2017 - Zillius Solutions (www.zillius.com.br)
  Code changes not allowed, doing so will lose warranty of its functionality!

  All rights reserved.

 * ******************************************************************************
 */

require_once(PATH_ROOT . "/lib/components/cropcanvas/class.cropcanvas.php");

function resize_jpg($input, $new_width, $new_height, $keepratio, $side)
{

	$imagedata = getimagesize($input);
	$w = $imagedata[0];
	$h = $imagedata[1];

	if ($keepratio)
	{
		if($side == 'H')
		{
			$new_w = ($new_height / $h) * $w;
			$new_h = $new_height;
		}
		elseif($side == 'W')
		{
			$new_h = ($new_width / $w) * $h;
			$new_w = $new_width;
		}
	}
	else
	{
		$new_w = $new_width;
		$new_h = $new_height;
	}

    switch ($image_type) 
     {
         case 1: $image = imagecreatefromgif($img); break;
         case 2: $image = imagecreatefromjpeg($img);  break;
         case 3: $image = imagecreatefrompng($img); break;
         default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
     }
	$im2 = imagecreatetruecolor($new_w, $new_h);

	imagecopyresampled ($im2, $image, 0, 0, 0, 0, $new_w, $new_h, $imagedata[0], $imagedata[1]);
	return $im2;

}


function crop_center($img_path, $width, $height)
{
	$cc =& new CropCanvas(true);

	if ($cc->loadImage($img_path))
		$img = $cc->_imgOrig;
	else
		return false;
		
	$img_width  = imagesx($img);
	$img_height = imagesy($img);
	
	// New image size
	$width  = $width;
	$height = $height;
	
	// Starting point of crop
	$tlx = floor($img_width / 2) - floor ($width / 2);
	$tly = floor($img_height / 2) - floor($height / 2);
	
	// Adjust crop size if the image is too small
	if ($tlx < 0)
	{
		$tlx = 0;
	}
	if ($tly < 0)
	{
		$tly = 0;
	}
	
	if (($img_width - $tlx) < $width)
	{
		$width = $img_width - $tlx;
	}
	if (($img_height - $tly) < $height)
	{
		$height = $img_height - $tly;
	}
	
	$im = imagecreatetruecolor($width, $height);
	imagecopy($im, $img, 0, 0, $tlx, $tly, $width, $height);
	return $im;
}

function crop_thumbnail($imgSrc,$thumbnail_width,$thumbnail_height) { //$imgSrc is a FILE - Returns an image resource.
    //getting the image dimensions  
    list($width_orig, $height_orig) = getimagesize($imgSrc);   
    $myImage = imagecreatefromjpeg($imgSrc);
    $ratio_orig = $width_orig/$height_orig;
    
    if ($thumbnail_width/$thumbnail_height > $ratio_orig) {
       $new_height = $thumbnail_width/$ratio_orig;
       $new_width = $thumbnail_width;
    } else {
       $new_width = $thumbnail_height*$ratio_orig;
       $new_height = $thumbnail_height;
    }
    
    $x_mid = $new_width/2;  //horizontal middle
    $y_mid = $new_height/2; //vertical middle
    
    $process = imagecreatetruecolor(round($new_width), round($new_height)); 
    
    imagecopyresampled($process, $myImage, 0, 0, 0, 0, $new_width, $new_height, $width_orig, $height_orig);
    $thumb = imagecreatetruecolor($thumbnail_width, $thumbnail_height); 
    imagecopyresampled($thumb, $process, 0, 0, ($x_mid-($thumbnail_width/2)), ($y_mid-($thumbnail_height/2)), $thumbnail_width, $thumbnail_height, $thumbnail_width, $thumbnail_height);

    imagedestroy($process);
    imagedestroy($myImage);
    return $thumb;
}

function resize_crop($filename, $width, $height, $crop_width, $crop_height)
{
	// Get new dimensions
	list($width_orig, $height_orig) = getimagesize($filename);
	
	$ratio_orig = $width_orig/$height_orig;
	/*
	if ($width/$height > $ratio_orig) {
	   $width = $height*$ratio_orig;
	} else {
	   $height = $width/$ratio_orig;
	}
	*/
	if ($width/$height > $ratio_orig) {
	   $height = $width/$ratio_orig;
	   $width = $width;
	} else {
	   $width = $height*$ratio_orig;
	   $height = $height;
	}
	
	$x_mid = $width/2;  //horizontal middle
	$y_mid = $height/2; //vertical middle
	$process = imagecreatetruecolor(round($width), round($height)); 
	
	// Resample
	$image_p = imagecreatetruecolor($width, $height);
	$image = imagecreatefromjpeg($filename);
	
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	$thumb = imagecreatetruecolor($crop_width, $crop_height); 
	
	imagecopyresampled($thumb, $image_p, 0, 0, ($x_mid-($crop_width/2)), ($y_mid-($crop_height/2)), $crop_width, $crop_height, $crop_width, $crop_height);
	
	return $thumb;
}
?>