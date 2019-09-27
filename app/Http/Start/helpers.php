<?php

/**
 * Helpers
 *
 * @package     Makent
 * @subpackage  Start
 * @category    Helper
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Http\Start;

use View;
use Session;
use App\Models\Metas;
use Image;
use File;
use JWTAuth;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;

class Helpers
{

	// Get current controller method name
	public function current_action($route)
	{
		$current = explode('@', $route); // Example $route value: App\Http\Controllers\HomeController@login
		View::share('current_action',$current[1]); // Share current action to all view pages
	}

	// Set Flash Message function
	public function flash_message($class, $message,$url='')
	{
		Session::flash('alert-class', 'alert-'.$class);
        Session::flash('message', $message);
	}

	// Dynamic Function for Showing Meta Details
	public static function meta($url, $field)
	{
		$metas = Metas::where('url', $url);
	
		if($metas->count())
			return $metas->first()->$field;
		else if($field == 'title')
			return 'Page Not Found';
		else
			return '';
	}

	public function compress_image($source_url, $destination_url, $quality, $width = 225, $height = 225) 
	{
        // $exif = exif_read_data($source_url);     

        $info = getimagesize($source_url);
        if(!$info)
        {
            return false;
        }
            if ($info['mime'] == 'image/jpeg')
                    $image = imagecreatefromjpeg($source_url);

            elseif ($info['mime'] == 'image/gif')
                    $image = imagecreatefromgif($source_url);

        elseif ($info['mime'] == 'image/png')
                    $image = imagecreatefrompng($source_url);


         // if (!empty($exif['Orientation'])) {
                
         //        $imageResource = imagecreatefromjpeg($source_url); // provided that the image is jpeg. Use relevant function otherwise
         //        switch ($exif['Orientation']) {
         //            case 3:
         //            $image = imagerotate($imageResource, 180, 0);
         //            break;
         //            case 6:
         //            $image = imagerotate($imageResource, -90, 0);
         //            break;
         //            case 8:
         //            $image = imagerotate($imageResource, 90, 0);
         //            break;
         //            default:
         //            $image = $imageResource;
         //        } 
         //    }

            imagejpeg($image, $destination_url, $quality);

        $this->crop_image($source_url, $width, $height);

            return $destination_url;

    }

    public function crop_image($source_url='', $crop_width=225, $crop_height=225, $destination_url = ''){
        ini_set('memory_limit', '256M');
        $image = Image::make($source_url);
        $image_width = $image->width();
        $image_height = $image->height();

        if($image_width < $crop_width && $crop_width < $crop_height){
            $image = $image->fit($crop_width, $image_height);
        }if($image_height < $crop_height  && $crop_width > $crop_height){
            $image = $image->fit($crop_width, $crop_height);
        }

        // if($image_width > $image_height){
        // 	$primary_crop_width = $image_height; 
        // 	$primary_crop_height = $image_height;

        // 	$primary_x = round(($image_width - $image_height)/2);
        // 	$primary_y = 0; 

        // }if($image_width <= $image_height){
        // 	$primary_crop_width = $image_width; 
        // 	$primary_crop_height = $image_width; 

        // 	$primary_x = 0;
        // 	$primary_y = $image_width < $image_height ? round(($image_height - $image_width)/2) : 0; 
        		
        // }

        // $primary_cropped_image = $image->crop($primary_crop_width, $primary_crop_height, $primary_x, $primary_y); 
  		$primary_cropped_image = $image;

        $croped_image = $primary_cropped_image->fit($crop_width, $crop_height);

		if($destination_url == ''){
			$source_url_details = pathinfo($source_url); 
			$destination_url = @$source_url_details['dirname'].'/'.@$source_url_details['filename'].'_'.$crop_width.'x'.$crop_height.'.'.@$source_url_details['extension']; 
		}
		$croped_image->save($destination_url); 
		return $destination_url; 
    }

 	public function phone_email_remove($message)
    {    
        $replacement = "[removed]";

  $dots=".*\..*\..*";

        $email_pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
        $url_pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+[\.][^\.\s]+[A-Za-z0-9\?\/%&=\?\-_]+/i";
        $phone_pattern = "/\+?[0-9][0-9()\s+]{4,20}[0-9]/";

        $find = array($email_pattern, $phone_pattern);
        $replace = array($replacement, $replacement);

        $message = preg_replace($find, $replace, $message);
        if($message==$dots)
        {

        $message = preg_replace($url_pattern, $replacement, $message);
    }
    else
{
        $message = preg_replace($find, $replace, $message);
      }

        
        return $message;
    }

    //export files to alignment 
     public static function buildExcelFile($filename, $data, $width = array())
    {
        /** @var \Maatwebsite\Excel\Excel $excel */
        $excel = app('excel');

        $excel->getDefaultStyle()
        ->getAlignment()
        ->setHorizontal('left');
        foreach ($data as $key => $array) {
            foreach ($array as $k => $v) {
                if(!$v){
                    $data[$key][$k] = '--';
                }
            }
        }

        // dd($filename, $data, $width);
        return $excel->create($filename, function (LaravelExcelWriter $excel) use($data, $width){
            $excel->sheet('exported-data', function (LaravelExcelWorksheet $sheet) use($data, $width) {
                $sheet->fromArray($data)->setWidth($width);
                $sheet->setAllBorders('thin');
            });
        });
    }

    public function fileUpload($file, $file_save_path = 'public', $file_name = '') {
        if ($file_name == '') {
            $fullName = $file->getClientOriginalName();
            $ext = $file->getClientOriginalExtension();
            $file_name = explode('.'.$ext,$fullName)[0];

        }
        $extension = '.' . $file->getClientOriginalExtension();
        $file_name = str_slug($file_name, '-') . time() . $extension;
        if (!file_exists(dirname($_SERVER['SCRIPT_FILENAME']).$file_save_path)) {
                //create image folder
                mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . $file_save_path, 0777, true);
            }
        $imgHolder = Image::make($file);    
        $imgHolder->save(public_path($file_save_path.$file_name));
        return $file_name;

    }

    public function cloud_upload($file,$last_src="",$resouce_type="image")
    {
         $site_name = str_replace(".","",SITE_NAME);
        try 
        {

            $options = [
                'folder' => $site_name.'/',
            ];
            if($resouce_type=="video")
            {
                \Cloudder::uploadVideo($file, null, $options);
            }
            else
            {
                \Cloudder::upload($file, null, $options);    
            }        
            $c=\Cloudder::getResult();
            // if($last_src!="") \Cloudder::destroy($last_src);
            $data['status']="success";
            $data['message']=$c;
        }
        catch (\Exception $e) 
        {
            $data['status']="error";
            $data['message']=$e->getMessage();
        }
        return $data;       
    }
    public function custom_strtotime($date, $prev_format = '')
    {
        if($prev_format == '')
        {
            if(PHP_DATE_FORMAT=="d/m/Y" || PHP_DATE_FORMAT=="m-d-Y")
            {
                $seperator=(PHP_DATE_FORMAT=="d/m/Y")? "/" : "-";
                $explode_date=explode($seperator,$date);
                if(count($explode_date)=="1")
                {
                    return strtotime($date);
                }
                else
                {
                    $original_date=$explode_date[1].$seperator.$explode_date[0].$seperator.$explode_date[2];  
                    return strtotime($original_date);     
                }            
            }
            else
            {
                return strtotime($date);
            }
        }
        else
        {
            $date_time = \DateTime::createFromFormat($prev_format, $date);
            return @$date_time->format('U');
        }
    }
    // get stripe supported currency
    public function getStripeCurrency($country = '')
    {
        $currency = [];
        $currency['AT'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['AU'] = ['AUD'];
        $currency['BE'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['CA'] = ['CAD','USD'];
        $currency['GB'] = ['GBP','EUR','DKK','NOK','SEK','USD','CHF'];
        $currency['HK'] = ['HKD'];
        $currency['JP'] = ['JPY'];
        $currency['NZ'] = ['NZD'];
        $currency['SG'] = ['SGD'];
        $currency['US'] = ['USD'];
        $currency['CH'] = ['CHF','EUR','DKK','GBP','NOK','SEK','USD'];
        $currency['DE'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['DK'] = ['DKK','EUR','GBP','NOK','SEK','USD','CHF'];
        $currency['ES'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['FI'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['FR'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['IE'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['IT'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['LU'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['NL'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['NO'] = ['NOK','EUR','DKK','GBP','SEK','USD','CHF'];
        $currency['PT'] = ['EUR','DKK','GBP','NOK','SEK','USD','CHF'];
        $currency['SE'] = ['SEK','EUR','DKK','GBP','NOK','USD','CHF'];
        if($country != '')
        {
            $currency = $currency[$country];
        }
        return $currency;

    }

    // get currency_code
    public function get_user_currency_code()
    {
        if (request('token')) {
            $user_details = JWTAuth::parseToken()->authenticate();
            $currency_code = $user_details->currency_code;
        } else {
            $currency_code = DEFAULT_CURRENCY;

        }
        return $currency_code;
    }


    public function resizeImage($resize_file_path,/*$resize_width,$resize_height,*/$resize_path)
    {

        $img = Image::make($resize_file_path);    
        $width  = $img->width();
        $height = $img->height();

        $dimension = 1000;

        $vertical   = (($width < $height) ? true : false);
        $horizontal = (($width > $height) ? true : false);
        $square     = (($width = $height) ? true : false);

        if ($vertical) {
            $top = $bottom = 245;
            $newHeight = ($dimension) - ($bottom + $top);
            $img->resize(null, $newHeight, function ($constraint) {
                $constraint->aspectRatio();
            });

        } else if ($horizontal) {
            $right = $left = 245;
            $newWidth = ($dimension) - ($right + $left);
            $img->resize($newWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            });

        } else if ($square) {
            $right = $left = 245;
            $newWidth = ($dimension) - ($left + $right);
            $img->resize($newWidth, null, function ($constraint) {
                $constraint->aspectRatio();
            });

        }

        // $img->resizeCanvas($dimension, $dimension);
        $img->save($resize_path);

        
        


    }

    public function remove_special_chars($string)
    {
        $string = str_replace(' ', '', $string);
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    }
}
