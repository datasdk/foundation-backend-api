<?php

namespace App\Traits\Date;

use DateTimeInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

trait Date
{
  

    public function getDateFormat($df = "ios")
    {
     
        $dateformats = $this->getDateAllDateFormats();

        return isset($dateformats[$df]["date"]) ? $dateformats[$df]["date"] : $dateformats['iso']["date"]; 

    }


    public function getTimeFormat($df = "ios")
    {
      
        $dateformats = $this->getDateAllDateFormats();

        return isset($dateformats[$df]["time"]) ? $dateformats[$df]["time"] : $dateformats['iso']["time"];

    }


    protected function serializeDate(DateTimeInterface $date)
    {

        $df = $this->getDateFormatFromRequest();

        // Get the date and time format separately
        $dateFormat = $this->getDateFormat($df);
        $timeFormat = $this->getTimeFormat($df);

        // Return the formatted date and time
        return $date->format($dateFormat . " " . $timeFormat);
    }

    protected function getDateFormatFromRequest(){

        $request = request();

        return $request->has('dateformat') ? $request->get('dateformat') : "iso";

    }
    

    
    protected function getDateAllDateFormats(){

        return $dateformats = config("dateformats.formats");

    }

}
