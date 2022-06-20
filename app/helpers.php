<?php
use \Morilog\Jalali\CalendarUtils;
if(!function_exists('convertJalaliToGeorgian')){

    function convertJalaliToGeorgian($date){
        $dateString = CalendarUtils::convertNumbers($date, true);
        return CalendarUtils::createCarbonFromFormat('Y/m/d', $dateString)->format('Y-m-d H:i:s');
    }


}
