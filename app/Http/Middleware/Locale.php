<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class Locale
{

    protected $formats = [
        "da" => ["date" => "d-m-Y", "time" => "H:i"],
        "en" => ["date" => "Y-m-d", "time" => "H:i"],
    ];
  
    
    public function handle(Request $req, Closure $next, ...$guards)
    {

      

        if($req->has("dateformat")){
            
            $format = $req->dateformat;

            if($format === "timestamp"){
                
                config(["app.date_output" => $format]);

            }

            else

            if(isset($this->formats[$format])){

                $df = $this->formats[$format];        

                config(["app.date_format"=>$df["date"]]);
                
                config(["app.time_format"=>$df["time"]]);

            }
            
        }

        
        return $next($req);
    

    }
}
