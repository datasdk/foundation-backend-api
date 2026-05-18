<?php

namespace App\Traits\DateFormat;

use DateTimeInterface;

trait DateFormat
{
   

    protected function serializeDate(DateTimeInterface $date)
    {

        $request = request();

        // Hent ønsket format (default: iso)
        $df = $this->getDateFormatFromRequest();

        $dateFormat = $this->dateFormat($df);

        $timeFormat = $this->timeFormat($df);

        // show_date og show_time = true som default
        $show_date = $request->has('show_date') ? $request->boolean('show_date') : true;
        
        $show_time = $request->has('show_time') ? $request->boolean('show_time') : true;


        // Kombiner formater
        if ($show_date && $show_time) {

            $format = "$dateFormat $timeFormat";

        } elseif ($show_date) {

            $format = $dateFormat;

        } elseif ($show_time) {

            $format = $timeFormat;

        } else {

            // Hvis intet vises – fallback til standard
            $format = "$dateFormat $timeFormat";

        }


        return $date->format($format);

    }


    private function getDateFormatFromRequest()
    {

        return request()->get('dateformat', 'iso');

    }


    private function getDateAllDateFormats()
    {

        return config('dateformats.formats');

    }

    private function dateFormat($df = 'iso')
    {

        $formats = $this->getDateAllDateFormats();

        if (!is_array($formats)) {
            $formats = [
                'iso' => [
                    'date' => 'Y-m-d',
                    'time' => 'H:i:s',
                ],
            ];
        }

        return $formats[$df]['date'] ?? $formats['iso']['date'];

    }


    private function timeFormat($df = 'iso')
    {

        $formats = $this->getDateAllDateFormats();

        if (!is_array($formats)) {
            $formats = [
                'iso' => [
                    'date' => 'Y-m-d',
                    'time' => 'H:i:s',
                ],
            ];
        }

        return $formats[$df]['time'] ?? $formats['iso']['time'];

    }

}
