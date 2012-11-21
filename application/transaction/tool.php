<?php

namespace App\Transaction;

use \App\Model\Article as Model_Article;
use \App\Model\Articlecategory as Model_Articlecategory;

class Tool {

    public static function generate_sitemap() {
        $site = 'http://' . SITENAME;
        $str = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
         xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		<url>
      <loc>' . $site . '</loc>
      <lastmod>' . date('Y-m-d') . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>1</priority>
   </url>
		<url>
      <loc>' . $site . '/about-us.php</loc>
      <lastmod>' . date('Y-m-d') . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>1</priority>
   </url>
		<url>
      <loc>' . $site . '/contact-us.php</loc>
      <lastmod>' . date('Y-m-d') . '</lastmod>
      <changefreq>daily</changefreq>
      <priority>1</priority>
   </url>
		';
        $cats = Model_Articlecategory::get_all_active_cats();
        $link_prefix = $site. "/front/article/category/";
        foreach ($cats as $cat) {
            $cat_str = '<url>
      <loc>' . $link_prefix . $cat['title'] . '</loc>
      <lastmod>' . date('Y-m-d') . '</lastmod>
      <changefreq>weekly</changefreq>
      <priority>0.8</priority>
   </url>';
            $str .= $cat_str;
        }
        $articles = Model_Article::get_all_active_articles();
        //\Zx\Test\Test::object_log('$articles', $articles, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $link_prefix = $site . "/front/article/content/";
        foreach ($articles as $article) {
        //\Zx\Test\Test::object_log('$articles',$article['id'], __FILE__, __LINE__, __CLASS__, __METHOD__);
            $article_str = '<url>
      <loc>' . $link_prefix . $article['url'] . '</loc>
      <lastmod>' . date('Y-m-d') . '</lastmod>
      <changefreq>weekly</changefreq>
      <priority>0.8</priority>
   </url>';
            $str .= $article_str;
        }
        $str .= '</urlset>';
        file_put_contents(PHP_ROOT . 'sitemap.xml', $str);
        return true;
    }
/**
     *
     * @param string $img_s  源文件
     * @param string $watermark 水印文件路径+文件名  must be a png file, otherwise change the function name
     * @param string $img_d  目标文件路径+文件名
     * image/fyl_images/water-mark.png
     * if use png, must use imagecopyresampled, if use jpg, we can use imagecopyemerge
     * 
     * 	$watermark = "x/water-mark.png";
	//$watermark = "x/logo-company.jpg";
	//$watermark = "x/ad04.jpg";
	//$img_s = "x/1.jpg";
	$img_s = "x/middle-ad3.jpg";
	$img_d = "x/m7.jpg";
	//$img_d = "x/m2.jpg";
	add_watermark($img_s, $watermark, $img_d);
     * 
     * 
     * 
     */
        public static function add_watermark($img_s,$watermark, $img_d) {
        $watermark = imagecreatefrompng($watermark);
        //$watermark = imagecreatefromjpeg($watermark);
        $watermark_width = imagesx($watermark);
        $watermark_height = imagesy($watermark);
        
        //$image_d = imagecreatetruecolor($watermark_width, $watermark_height);
        $image = imagecreatefromjpeg($img_s);
        $size = getimagesize($img_s);
        $dest_x = $size[0] - $watermark_width - 5;
        $dest_y = $size[1] - $watermark_height - 5;
		//$dest_x = 10;
		//$dest_y = 10;
        
        //imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 20);
        imagecopyresampled($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $watermark_width, $watermark_height);
        //header('content-type: image/png');
        header('content-type: image/jpeg');
		//imagesavealpha($image,true);
        //imagepng($image, $img_d, 0);
        imagejpeg($image, $img_d);
        imagedestroy($image);
        imagedestroy($watermark);
		//var_dump($size);
		//var_dump($watermark_width);
		//var_dump($watermark_height);
    }
	
    public static function gbk_substr($string, $length, $dot = '  ......', $charset = 'gbk') {
        if (strlen($string) <= $length) {
            return $string;
        }
        $string = str_replace(array(' ', ' ', '&', '"', '<', '>'), array('', '', '&', '"', '<', '>'), $string);
        $strcut = '';
        if (strtolower($charset) == 'gbk') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t < 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
        } else {
            for ($i = 0; $i < $length; $i++) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i] . $string[++$i] : $string[$i];
            }
        }
        return $strcut . $dot;
    }

    /**
     * generate keyword array for search form, all none-alphabetical/none-digital character are removed
     * @param string $keywords_string such as $s = "abc    345   		 9/78ax, ppp";
     * @return array such as ('abc','345','9','78ax','ppp') if have values, otherwise return false
     */
    public static function get_keywords_array($keywords_string = '') {
        $pattern = '/\w+/';
        if (preg_match_all($pattern, $keywords_string, $matches)) {
            return $matches[0];
        } else {
            return false;
        }
    }
 /**
     * @param: int $image_width: dimension of image
     * @param: int $image_height: dimension of image
     * @param: int $digits: the number of digits that will be displayed in the image
     * @param: int $string_font_size: this will be affected by font type and image dimension
     * @param: array $string_color: the color of the string, it's an associate array
     *         $string_color = array('red'=>, 'green'=>, 'blue'=>)
     * @usage: generateVerificationCode(600, 200, 7, 18,
      array('red' => 30, 'green' => 10, 'blue' => 20);
     * @flow: For display string clearly, use the following sequence:
      display background: white
      display random dots and lines
      display string
     */
    public static function generateVerificationCode($image_width, $image_height, $digits, $string_font_size, $string_color
    ) {

        /**
          create a new image resouce
         */
        $im = imagecreatetruecolor($image_width, $image_height);
        /**
          fill with background color: white
         */
        $background_color = imagecolorallocate($im, 255, 255, 255);
        imagefill($im, 0, 0, $background_color);
        /**
          $i is for loop,
          $j=1 draw a dot, $j=-1 draw a line,
          $k=1 right direction,  $k=-1 left direction
          $x1, $y1  coordinate for dot or start coordinate for line
          $x2, $y2  end coordinate for line
          $rand_color dot color or line color
         */
        for ($i = 0, $j = 1, $k = 1; $i <= 50; $i++) {
            $rand_color = imagecolorallocate($im, rand(150, 200), rand(150, 200), rand(150, 200));
            $x1 = rand(0, $image_width);
            $y1 = rand(0, $image_height);
            if ($j == 1) {
                imagesetpixel($im, $x1, $y1, $rand_color);
            } else {
                //can be near/up or far/down several pixels between 1 and 20  
                $x2 = $x1 + rand(1, 20);
                $y2 = $y1 + rand(1, 20) * $k;
                imagesetthickness($im, rand(1, 3));  //width of line
                imageline($im, $x1, $y1, $x2, $y2, $rand_color);
                $k *= -1;  //change direction
            }
            $j *= -1;  // change between dot and line
        }
        /**
          start coordinate of string, can be adjusted according to dimension and font
         */
        $str_x = rand(10, 15);
        $str_y = rand(30, 40);

        /**
          if want to display n digits
          $symbol_array only store clear characters, not store '0','o','2','z','1','i', etc.
         */
        $symbol_array = array_merge(range('3', '8'), array('a', 'c', 'e', 'f', 'h', 'j', 'k', 'm', 'n', 'r', 's', 't', 'w', 'x', 'y'));
        $symbol_length = count($symbol_array);
        $rand_number = abs(rand(pow($symbol_length, $digits + 1), pow($symbol_length, $digits + 2) - 1));

        //$_SESSION['VCODE'] = '';
        $vcode = '';

        /**
          $i is for loop, $j is for direction: near/up or far/down
         */
        
        //App_Test::objectLog('$vcode:',DOCROOT . 'image/icon/arial.ttf',__FILE__, __LINE__, __CLASS__, __METHOD__);	
        for ($i = 1, $j = 1; $i <= $digits; $i++) {
            $remainder = $symbol_array[$rand_number % $symbol_length];  //get the last digit
            $rand_number = floor($rand_number / $symbol_length);  //get the first n digits except the last digit
            $rotate_degree = rand(0, 80) - 40;  //rotate from -40 to 40 degrees counter clockwise
            $str_color = imagecolorallocate($im, $string_color['red'], $string_color['green'], $string_color['blue']);  //string color
            //imagettftext($im, $string_font_size, $rotate_degree, $str_x, $str_y, $str_color, PHP_ROOT . 'image/icon/arial.ttf', $remainder);
            imagettftext($im, $string_font_size, $rotate_degree, $str_x, $str_y, $str_color, PHP_ROOT . 'image/icon/impact.ttf', $remainder);
            //make sure session_start() in the main program, VCODE means verification code
            //$_SESSION['VCODE'] .= $remainder;
            $vcode .= $remainder;
            /**
              new coordinate
             */
            $str_x += rand(20, 30);  //near or far several random pixels between 20 and 50
            $j *= -1;
            $str_y += rand(1, 10) * $j; //up or down several random pixels between 1 and 20
        }
            //\Zx\Test\Test::object_log('$vcode', $vcode, __FILE__, __LINE__, __CLASS__, __METHOD__);
        $_SESSION['VCODE'] = $vcode;
        header('Content-type: image/jpeg');
        //$image_file = str_replace(' ','.', microtime()).'.jpeg';
        //imagejpeg($im, $image_file);
        imagejpeg($im);
        imagedestroy($im);
        //return array('rand_number'=>$rand_number, 'image'=>$image_file);
    }
    
}