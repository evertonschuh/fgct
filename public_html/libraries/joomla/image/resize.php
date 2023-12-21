<?php

defined('JPATH_PLATFORM') or die;


class JResize 
{

	public function __construct()
	{

		if (!extension_loaded('gd') && !extension_loaded('gd2')) 
		{
			trigger_error("GD não foi carregado", E_USER_WARNING);
			return false;
		}
	}
	
	public function SimpleCopyGifAnimated($img, $newfilename, $force_new = false)
	{
		

		if($force_new){
			if ( file_exists($newfilename)) {
				unlink($newfilename);
			}
		}
		else{
			if ( file_exists($newfilename) ) 
			return $newfilename;
		}
	
		
		copy($img, $newfilename);
		//imagegif($img,$newfilename);
		return $newfilename;
	}
	
	public function resize($img, $w, $h, $newfilename, $force_new = false, $scale = 1) 
	{

		if (!extension_loaded('gd') && !extension_loaded('gd2')) 
		{
			trigger_error("GD não foi carregado", E_USER_WARNING);
			return false;
		}

        if(!file_exists($img))
            return false;
        
		$imgInfo = getimagesize($img);
		switch ($imgInfo[2]) 
		{
			case 1: $im = imagecreatefromgif($img); break;
			case 2: $im = imagecreatefromjpeg($img);  break;
			case 3: $im = imagecreatefrompng($img); break;
			default:  trigger_error('Formato não suportado!', E_USER_WARNING);  break;
		}
		//Se a imagem e pequena não redimensiona


		if($force_new){
			if ( file_exists($newfilename)) {
				unlink($newfilename);
			}
		}
		else{
			if ( file_exists($newfilename) ) 
			return $newfilename;
		}
		

		if ($scale=='tirarProporcao' || $scale==0)
		{

			$nHeight =$h;
			$nWidth =  $w;
			$nWidth = round($nWidth);
			$nHeight = round($nHeight);
			$newImg = imagecreatetruecolor($nWidth, $nHeight);
			/* Checando se a imagem ã PNG ou GIF, então seta como transparent*/  
			if(($imgInfo[2] == 1)  or ($imgInfo[2] == 3))
			{
				imagealphablending($newImg, false);
				imagesavealpha($newImg,true);
				$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
				imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
			}
			imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);
			//Gera o arquivo e renomeia com $newfilename
			switch ($imgInfo[2])
			{
				case 1: imagegif($newImg,$newfilename); break;
				case 2: imagejpeg($newImg,$newfilename);  break;
				case 3: imagepng($newImg,$newfilename); break;
				default:  trigger_error('Falhou ao redimensionar a imagem!', E_USER_WARNING);  break;
			}   
	   }
	   else
	   {
		   			
			$nx = 0;
			$ny = 0;
			$ix = 0;
			$iy = 0;  
			$iWidth = $imgInfo[0];
			$iHeigth = $imgInfo[1];
			
			if($scale=='cortarProporcao' || $scale==2)
			{
				if ($iWidth  ==  $iHeigth )
				{
					$nx = 0;
					$ny = 0;
					$ix = 0;
					$iy = 0;  
					$nHeight =$h;
					$nWidth=$w;
					$iWidth = $imgInfo[0];
					$iHeigth = $imgInfo[1];
				}
				else
				{		
					$nWidth = $w; 
					$nHeight = $h;	
												
					if ($iWidth > $iHeigth )
					{
						$fator = ($iHeigth/$h);
						$ix = ($iWidth - $w*$fator)/2; 
						$iWidth = $w*$fator; 	
					}
					else
					{
						$fator = ($iWidth/$w);
						$iy = ($iHeigth - $h*$fator)/2; 
						$iHeigth = $h*$fator; 	
					}
				}
			}
			else
			{
		   
			   	if ($iWidth <= $w && $iHeigth  <= $h)
				{
					$nHeight = $iHeigth ;
					$nWidth = $iWidth;
				}
				else
				{						
					if ($iWidth > $iHeigth )
					{
						
						$nWidth = $w; 
						$nHeight = $iHeigth *($w/$iWidth);
						if ($nHeight > $h)
						{
							$nHeight = $h; 
							$nWidth = $iWidth*($h/$iHeigth );
						}
						
					}
					else
					{
						$nHeight = $h; 
						$nWidth = $iWidth*($h/$iHeigth );
						if ($nWidth > $w)
						{
							$nWidth = $w; 
							$nHeight = $iHeigth *($w/$iWidth);
						}
					}
				}
				
	   		}
			  
			$nWidth = round($nWidth);
			$nHeight = round($nHeight);
			$newImg = imagecreatetruecolor($nWidth, $nHeight);
			/* Checando se a imagem ã PNG ou GIF, então seta como transparent*/  
			if(($imgInfo[2] == 1)  or ($imgInfo[2] == 3))
			{
				imagealphablending($newImg, false);
				imagesavealpha($newImg,true);
				$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
				imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
			}
			
			
			// imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )

			imagecopyresampled($newImg, $im, $nx, $ny, $ix, $iy, $nWidth, $nHeight, $iWidth, $iHeigth);
			//Gera o arquivo e renomeia com $newfilename				
			switch ($imgInfo[2])
			{
				case 1: imagegif($newImg,$newfilename); break;
				case 2: imagejpeg($newImg,$newfilename,60);  break;
				case 3: imagepng($newImg,$newfilename,9,0); break;
				default:  trigger_error('Falhou ao redimensionar a imagem!', E_USER_WARNING);  break;
			}

		}
		imagedestroy($newImg);
		switch ($imgInfo[2]) 
		{
			case 1: $typeImg ='image/gif'; break;
			case 2: $typeImg = 'image/jpeg';  break;
			case 3: $typeImg = 'image/png'; break;
			default:  trigger_error('Formato não suportado!', E_USER_WARNING);  break;
		}

		if (isset($this->_document->datauri) && $this->_document->datauri === true)
			return 'data:'.$typeImg.';base64,' . base64_encode ( file_get_contents ( $newfilename ) );
		else
			return $newfilename;

		
	}
	public function thumb($src, $dest, $width, $height, $scale = false)
    {
        @unlink($dest);
 
        if (!$infos = @getimagesize($src)) {
            return false;
        }
 
        $iWidth = $infos[0];
        $iHeight = $infos[1];
         
        //$iRatioW = $width / $iWidth;
        //$iRatioH = $height / $iHeight;
         
        //$iNewH = $height;
		if ($scale) {  
			if(empty($height))
				$height = $iHeight;
			 
			if(empty($width))
				$width = $iWidth;
				

			if ($iWidth <= $width && $iHeight <= $height) {
				$iNewW = $iWidth;
				$iNewH = $iHeight;
			}
			else {						
				if ($iWidth > $iHeight ) {
					$iNewW = $width; 
					$iNewH = $iHeight*($width/$iWidth);
					if ($iNewH>$height)	{
						$iNewH = $height; 
						$iNewW = $iWidth*($height/$iHeight);
					}
					
				}
				else {
					$iNewH = $height; 
					$iNewW = $iWidth*($height/$iHeight);
					if ($nWidth>$w) {
					$iNewW = $width; 
					$iNewH = $iHeight*($width/$iWidth);
					}
				}
			}		
		}
		else {
			if(!empty($height))
				$iNewH = $height;
			 
			if(!empty($width))
				$iNewW = $width;
		 
			if(empty($height))
				$iNewH = ($iHeight/$iWidth)*$iNewW;
			 
			///Condition if width blank then set autowidth... otherwise set passing width...
			if(empty($width))
				$iNewW = ($iWidth/$iHeight)*$iNewH;
	 
			//Don't resize images which are smaller than thumbs
			if ($infos[0] < $width && $infos[1] < $height) {
				$iNewW = $infos[0];
				$iNewH = $infos[1];
			}
		}
        if($infos[2] == 1) {
 
            $imgA = imagecreatefromgif($src);
            $imgB = imagecreate($iNewW,$iNewH);
             
            if(function_exists('imagecolorsforindex') && function_exists('imagecolortransparent')) {
                $transcolorindex = imagecolortransparent($imgA);
                    //transparent color exists
                    if($transcolorindex >= 0 ) {
                        $transcolor = imagecolorsforindex($imgA, $transcolorindex);
                        $transcolorindex = imagecolorallocate($imgB, $transcolor['red'], $transcolor['green'], $transcolor['blue']);
                        imagefill($imgB, 0, 0, $transcolorindex);
                        imagecolortransparent($imgB, $transcolorindex);
                    //fill white
                    } else {
                        $whitecolorindex = @imagecolorallocate($imgB, 255, 255, 255);
                        imagefill($imgB, 0, 0, $whitecolorindex);
                    }
            //fill white
            } else {
                $whitecolorindex = imagecolorallocate($imgB, 255, 255, 255);
                imagefill($imgB, 0, 0, $whitecolorindex);
            }
            imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
            imagegif($imgB, $dest);        
 
        } elseif($infos[2] == 2) {
 
            $imgA = imagecreatefromjpeg($src);
            $imgB = imagecreatetruecolor($iNewW,$iNewH);
            imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
            imagejpeg($imgB, $dest);
             
 
        } elseif($infos[2] == 3) {
            /*
            * Image is typ png
            */
            $imgA = imagecreatefrompng($src);
            $imgB = imagecreatetruecolor($iNewW, $iNewH);
            imagealphablending($imgB, false);
            imagecopyresampled($imgB, $imgA, 0, 0, 0, 0, $iNewW, $iNewH, $infos[0], $infos[1]);
            imagesavealpha($imgB, true);
            imagepng($imgB, $dest);
             
        } else {
            return false;
        }
        return true;
    }
	
	
	function &crop($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale)
	{
		list($imagewidth, $imageheight, $imageType) = getimagesize($image);	
		$imageType = image_type_to_mime_type($imageType);
		
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		
		
		
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		if(($imageType == "image/gif")  or ($imageType == "image/png") or ($imageType == "image/x-png"))
		{
			imagealphablending($newImage, false);
			imagesavealpha($newImage,true);
			$transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
			imagefilledrectangle($newImage, 0, 0, $newImageWidth, $newImageHeight, $transparent);
		}
		switch($imageType) 
		{
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 
				break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 
				break;
		}
		imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
		switch($imageType) 
		{
			case "image/gif":
				imagegif($newImage,$thumb_image_name); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage,$thumb_image_name,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$thumb_image_name);  
				break;
		}
		chmod($thumb_image_name, 0777);
		return $thumb_image_name;
	}

	
}
?>
