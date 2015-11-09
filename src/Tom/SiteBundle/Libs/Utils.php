<?php

namespace Tom\SiteBundle\Libs;

class Utils {

    static public function sluggify($text, $replace=array(), $delimiter='-') {
        
        setlocale(LC_ALL, 'pl_PL.UTF8');
	if( !empty($replace) ) {
            $text = str_replace((array)$replace, ' ', $text);
	}

	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        if(empty($clean)) {
            return NULL;
        }
        
        return $clean;
    }
    
    static public function removeImage($pathToImage, $img) {
        if(file_exists($pathToImage.$img)) {
           unlink($pathToImage.$img);
        }
        if(file_exists($pathToImage.'th_'.$img)) {
           unlink($pathToImage.'th_'.$img);
        }
        if(file_exists($pathToImage.'sm_'.$img)) {
           unlink($pathToImage.'sm_'.$img);
        }
        if(file_exists($pathToImage.'md_'.$img)) {
           unlink($pathToImage.'md_'.$img);
        }
    }
    
    static public function imageResize($img_path, $img_name, $img_prefix, $max_width, $max_height, $quality = 100) {

        $source_file = $img_path.$img_name;
        $dst_dir = $img_path.$img_prefix.$img_name;
        $imgsize = getimagesize($source_file);
        $width = $imgsize[0];
        $height = $imgsize[1];
        $mime = $imgsize['mime'];

        switch($mime){
            case 'image/gif':
                $image_create = "imagecreatefromgif";
                $image = "imagegif";
                break;

            case 'image/png':
                $image_create = "imagecreatefrompng";
                $image = "imagepng";
                $quality = 7;
                break;

            case 'image/jpeg':
                $image_create = "imagecreatefromjpeg";
                $image = "imagejpeg";
                $quality = 80;
                break;

            default:
                return false;
                break;
        }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
        $src_img = $image_create($source_file);

        $width_new = $height * $max_width / $max_height;
        $height_new = $width * $max_height / $max_width;

        if($width_new > $width){
            $h_point = (($height - $height_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
        } else{
            $w_point = (($width - $width_new) / 2);
            imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
        }

        $image($dst_img, $dst_dir, $quality);
        imagedestroy($dst_img);
    }
    
}
