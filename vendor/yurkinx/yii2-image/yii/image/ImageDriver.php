<?php
/**
 * Image.php
 * @author: Yuri Kileev <kileev@gmail.com>
 * @date  : 17.10.2013
 */

namespace yii\image;

use Yii;
use yii\base\Component;
use yii\base\ErrorException;
use yii\image\drivers\Image;

/**
 * Class ImageDriver
 * The main class to wrap Kohana Image Extension
 * @package yii\image
 */
class ImageDriver extends Component
{
      
        /**
        * @var  string  default driver: GD or ImageMagick
        */
        public $driver;
       
        /**
         * Loads the image to Kohana_Image object
         * @param string $file the file path to the image
         * @param string $driver the image driver to use: GD or ImageMagick
         * @throws ErrorException if filename is empty or file doesn't exist
         * @return mixed object Image_GD or object Image_Imagick 
         */
        public function load($file = null, $driver = null){
            
            if(empty($file)){
                 throw new ErrorException('File name can not be empty');
            }
            if(!realpath($file)){
                 throw new ErrorException(sprintf('The file doesn\'t exist: %s',$file));
            }
            return Image::factory($file, $driver ? $driver : $this->driver);
            
        }
}
?>