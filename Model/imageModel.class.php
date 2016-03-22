<?php
	/***********
	2015-01-27
	刘雪峰
	image处理类
	imageModel.class.php
	***/
	class imageModel{

		/*图片生成缩略图*/
        public function _pictureThumbBnail($path,$fileName){
            $image = $path.$fileName.".jpg";
            $thumbImage = $path.$fileName."_thumb_30.jpg";
            $imageWidth = 90;
            $imageHeight = 90;
            $size   = getimagesize($image); 
            $in_in  = imagecreatefrompng($image); 
            $in_out = imagecreatetruecolor($imageWidth,$imageHeight);  
            imagecopyresampled($in_out,$in_in,0,0,0,0,$imageWidth,$imageHeight,$size[0],$size[1]); 
            imagejpeg($in_out,$thumbImage); 
            imagedestroy($in_in); 
            imagedestroy($in_out); 
        }

        public function _createDir($path){
            
            if (!file_exists($path)) { 
                
                mkdir($path, 0733);
            
            }

        }

        //把上传的图片移动到指定路径
        public function _moveImage($file,$dir){

           return  move_uploaded_file($file,$dir);
        
        }

        //验证图片是否违法
        public function _checkImageLegal($tmpDir){
            $image = @file_get_contents($tmpDir);
            $im    = @imagecreatefromstring($image);
            return $im;
        }

	} 
?>