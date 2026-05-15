<?php

namespace App\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator; // Added this line
use App\Rules\Lang; // Added this line
use DataSDK\Categories\Models\Categories;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Event;
use Modules\Email\Models\MailTemplates;
use Illuminate\Support\Collection;
//use App\Helpers\RuleHelper;
use Illuminate\Support\Facades\Vite;
use App\Helpers\LogManager;
use App\Observers\ActionModelObserver;
use Lecturize\Addresses\Models\Address;
use Widget;
use App\Models\User;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Perform any booting services.
     *
     * This method is used for registering event listeners, observers, etc.
     */
    public function boot()
    {
        $this->registerCoreModuleResources();
        $this->registerObservers(); // Register the observers for models
        $this->viteConfig();

       // $this->registerCustomValidators(); // Register custom validation rules if necessary
        Paginator::useBootstrap(); // Use Bootstrap for pagination styling

        /*
        require app_path('Helpers/ConfigHelper.php');
        require app_path('Helpers/CustomHeaders.php');
        require app_path('Helpers/UserHelper.php');
        require app_path('Helpers/NavigationHelper.php');
   */

    }

    /**
     * Register any application services.
     *
     * This method is used for registering services and configurations
     * that need to be initialized at the start of the application.
     */
    public function register()
    {
       

        $this->checkPHPVersion(); // Check if the PHP version meets the required version
        $this->checkDBConnection(); // Check if the database connection is working
        $this->setTimezone(); // Set the application's timezone
        $this->setSettings(); // Set application-specific settings from the database
        $this->registerRules();
       // $this->setLanguage();
        $this->addWidgets();


    }

    /**
     * Check if the current PHP version meets the required version.
     *
     * @throws \Exception If the PHP version is lower than required.
     */
    private function checkPHPVersion()
    {
        $required_version = '7.4.0';

        if (version_compare(PHP_VERSION, $required_version, '<')) {
            die("The system requires PHP version {$required_version}, current version: " . PHP_VERSION . "\n");
        }
        
    }

    /**
     * Check if the database connection is established.
     *
     * @throws \Exception If there is an issue with the database connection.
     */
    private function checkDBConnection()
    {
        try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
            die("Could not connect to the database. Please check your configuration. Error: " . $e->getMessage());
        }
    }

    /**
     * Set the application's default timezone.
     *
     * This will use the timezone from the app configuration, defaulting to UTC.
     */
    private function setTimezone()
    {
   
        date_default_timezone_set(config("app.timezone", 'UTC'));
    }

    /**
     * Set application settings from the 'settings' database table.
     *
     * This method will merge settings from the database into the application's config.
     */
    private function setSettings()
    {
        try {
            if (!Schema::hasTable('settings')) {
                return; // Prevent errors if the table does not exist
            }

            $settings = DB::select('select * from settings');
        } catch (\Throwable $e) {
            return;
        }


        foreach ($settings as $setting) {


            $key = $setting->key;
            
            $value = unserialize($setting->value); // Deserialize the setting value

            $origConfig = config($key);


            if($origConfig && is_array($value)){

                $mergedSettings = array_merge(Arr::dot(config($key)), Arr::dot($value));
               
                Config::set($key, Arr::undot($mergedSettings)); 

            } else {

                $mergedSettings = Config::set($key, $value);
          
            }
                 
           
        }

       
        
    }


    public function setLanguage(){

        return Config::set("public.locales",config("app.locales"));

    }
    /**
     * Register model observers.
     *
     * This method is used for registering any observers for models.
     */
    protected function registerObservers()
    {
        
       // User::observe(UserObserver::class);


    }


    protected function registerCoreModuleResources(): void
    {
        $emailModulePath = base_path('Modules/Email');

        if (is_dir($emailModulePath)) {
            $this->loadMigrationsFrom($emailModulePath . '/Database/Migrations');
            $this->loadTranslationsFrom($emailModulePath . '/Resources/lang', 'email');
        }
    }


    protected function viteConfig(){

        Vite::useScriptTagAttributes([
            'defer' => true, // Specify an attribute without a value...
        ]);  

    }
    
    
    protected function registerRules(){
    
    /*
        RuleHelper::registerModels([
            \DataSDK\Categories\Models\Categories::class
        ]);
    */

    }
    
    protected function addWidgets(){

        Widget::group('dashboard')->position(1)->addWidget('dashboard_hello');
        
        Widget::group('dashboard')->position(2)->addWidget('dashboard_users');

        Widget::group('dashboard')->position(3)->addWidget('dashboard_logs');

        
    }

}
