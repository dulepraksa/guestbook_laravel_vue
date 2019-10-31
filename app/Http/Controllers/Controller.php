<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use App\User;
use Carbon\Carbon;
use \DateTime;
use \Time;
use \DateTimeZone;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function takePhoto($file) 
    {
        if ($file) {
            $exploded = explode(',', $file);
            $decoded = base64_decode($exploded[1]);
                if (str_contains($exploded[0],'jpeg')) { 
                    $extension = 'jpg';
                }
                else {
                    $extension = 'png';
                }
            $filename =  str_random().'.'.$extension;
            $main_dir = public_path().'/images'; 
            $path = $this->findFolderWithSpace($main_dir).$filename;
            $sub_folder_name = $this->subFolderName($path);
            file_put_contents($path,$decoded);
            return $file = [
                $filename, $path, $sub_folder_name
            ];
        }
        else {
            return [
                null,null,null
            ];
        }
    }
    public function takeFile($file) {
        if($file) {

            $exploded = explode(',' ,$file);
            $decoded = base64_decode($exploded[1]);
            
            $extension = self::base64_file_extension($exploded[0]);

            $filename =  str_random().'.'.$extension;
            $main_dir = public_path().'/nda';
            $path = $this->findFolderWithSpace($main_dir).$filename;
            $sub_folder_name = $this->subFolderName($path);
            file_put_contents($path,$decoded);
            return $file = [
                $filename, $path,$sub_folder_name
            ];
        }
        else {
            return $filename = '';
        }
    }
        public function findFolderWithSpace($main_dir)
        {
            $main_dir_count = $this->dirCount($main_dir);
            $curr_dir = $main_dir.'/'.$main_dir_count;
            $old_path = $curr_dir . '/';
            if(($this->dirCount($curr_dir)) == 200) {
                $main_dir_count++;
                $new_dir = $main_dir.'/'.$main_dir_count;
                    if(!is_dir($new_dir)) {
                        mkdir($new_dir,true);
                        $new_path = $new_dir .'/';    
                    }
                return $new_path;
            
            } else { 
                return $old_path;
            }
    
        }
        public function dirCount($dir)
        {
            return count(array_slice(scandir($dir),2));
        }


    public function base64_file_extension($raw_uri)
    {
        $mime = str_replace(';base64','', $raw_uri);
        $mime = str_replace('data:', '', $mime);

        $extension_array = [
            'application/msword' => 'docx',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => "docx",
            'application/pdf' => 'pdf'
        ];

        return $extension_array[$mime];
    }

    public function subFolderName($path)
    {

        $sub_folder_name = basename(dirname($path));

        return $sub_folder_name;
    }

    public function weekInterval() 
    {
        $week = [];
        $tz_object = new DateTimeZone('Europe/Belgrade');


        $this_week_start = strtotime('monday this week 00:00:00');
        $this_week_end = strtotime('friday this week 23:59:59');

        $start = date('Y-m-d H:i:s', $this_week_start);
        $end = date('Y-m-d H:i:s', $this_week_end);

        $new_start = DateTime::createFromFormat('Y-m-d H:i:s', $start, $tz_object);
        $new_end =  DateTime::createFromFormat('Y-m-d H:i:s', $end, $tz_object);

        return $week = [
            'first_day' => $new_start,
            'end_day' => $new_end
        ];
    }
    public function todayInterval()
    {
        $tz_object = new DateTimeZone('Europe/Belgrade');

        $thisDay = [];

        $today_start = strtotime('today 00:00:00');
        $today_end = strtotime('today 23:59:59');

        $start = date('Y-m-d H:i:s', $today_start);
        $end = date('Y-m-d H:i:s', $today_end);

        $new_start = DateTime::createFromFormat('Y-m-d H:i:s', $start, $tz_object);
        $new_end =  DateTime::createFromFormat('Y-m-d H:i:s', $end, $tz_object);


        return $thisDay = [
            'day_start' => $start,
            'day_end' => $end 
        ];
    }
    public function yesterdayInterval()
    {
        $tz_object = new DateTimeZone('Europe/Belgrade');


        $thisDay = [];

        $today_start = strtotime('-1 days 00:00:00');
        $today_end = strtotime('-1 days 23:59:59');

        $start = date('Y-m-d H:i:s', $today_start);
        $end = date('Y-m-d H:i:s', $today_end);

        $new_start = DateTime::createFromFormat('Y-m-d H:i:s', $start, $tz_object);
        $new_end =  DateTime::createFromFormat('Y-m-d H:i:s', $end, $tz_object);


        return $thisDay = [
            'day_start' => $start,
            'day_end' => $end 
        ];
    }
    public function monthInterval()
    {
        $tz_object = new DateTimeZone('Europe/Belgrade');


        $thisDay = [];

        $month_start = strtotime('first day of this month 00:00:00');
        $month_end = strtotime('last day of this month 23:59:59');

        $start = date('Y-m-d H:i:s', $month_start);
        $end = date('Y-m-d H:i:s', $month_end);

        $new_start = DateTime::createFromFormat('Y-m-d H:i:s', $start, $tz_object);
        $new_end =  DateTime::createFromFormat('Y-m-d H:i:s', $end, $tz_object);


        return $thisDay = [
            'day_start' => $start,
            'day_end' => $end 
        ];
    }
    public function requestDateFormat($date)
    {
        if($date) {
            $tz_object = new DateTimeZone('Europe/Belgrade');
    
            $date_str = strtotime($date);
            $new_date = date('Y-m-d H:i:s', $date_str);
            return DateTime::createFromFormat('Y-m-d H:i:s', $new_date, $tz_object)->format('Y-m-d H:i:s');
        } 
        else {
            return null;
        }
    }
}
