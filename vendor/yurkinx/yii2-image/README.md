yii2-image
==========

Simple to use Yii2 Framework extension for image manipulating using powerful Kohana Image Library.  Inspired by old yii extension 
http://www.yiiframework.com/extension/image/ and Kohana Image Library https://github.com/kohana/image

Installation
------------
```code
{
	"require": 
	{
  		"yurkinx/yii2-image": "dev-master"
	}
}
```
Configuration
-------------
In config file
```code
/config/web.php
```
Add image component
```code
'components' => array(
        ...
        'image' => array(
        	 	'class' => 'yii\image\ImageDriver',
        		'driver' => 'GD',  //GD or Imagick
        		),
		    )
```
Usage
-----
```php
$file=Yii::getAlias('@app/pass/to/file'); 
$image=Yii::$app->image->load($file);
header("Content-Type: image/png");
echo 	$image->resize($width,$height)->rotate(30)->render();
```

Supported methods out of the box from Kohana Image Library:
```php
$image->resize($width = NULL, $height = NULL, $master = NULL);
$image->crop($width, $height, $offset_x = NULL, $offset_y = NULL);
$image->sharpen($amount);
$image->rotate($degrees);
$image->save($file = NULL, $quality = 100);
$image->render($type = NULL, $quality = 100);
$image->reflection($height = NULL, $opacity = 100, $fade_in = FALSE);
$image->flip($direction);
$image->background($color, $opacity = 100);
$image->watermark(Image $watermark, $offset_x = NULL, $offset_y = NULL, $opacity = 100);
```
**Using resize with resize constrains**
```php
$image->resize($width, $height, \yii\image\drivers\Image::HEIGHT);
$image->resize($width, $height, \yii\image\drivers\Image::ADAPT)->background('#fff');
```
Possible resize constrains:
```php
// Resizing constraints ($master)
    const NONE    = 0x01;
    const WIDTH   = 0x02;
    const HEIGHT  = 0x03;
    const AUTO    = 0x04;
    const INVERSE = 0x05;
    const PRECISE = 0x06;
    const ADAPT   = 0x07;
    const CROP    = 0x08;
```
**Using flip with flipping directions**
```php
// Flipping directions ($direction)
$image->flip(\yii\image\drivers\Image::HORIZONTAL);
```
Possible flipping directions:
```php
     const HORIZONTAL = 0x11;
     const VERTICAL   = 0x12;
```

