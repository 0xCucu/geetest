<?php
namespace geetest\provider;
use geetest\geetest_base;
use Illuminate\Support\ServiceProvider;
use geetest\decode;
use geetest\DataValidate\Down_validate;
use geetest\DataValidate\UnDown_validate;
use geetest\Data_Request;
class geetestServiceProvider extends ServiceProvider
{

  public function boot()
  {
    $this->publishes([
           dirname(dirname(__FILE__)).'/Config/geetest.php' => config_path('geetest.php'),
    ]);
   
    # code...
  }
  public function register()
  {
     $this->app->bind('geetest',function(){
         return new geetest_base();
     });
      $this->app->singleton('geetest_decode',function(){
            return new decode();
      });
      $this->app->singleton('down',function(){
          return new  Down_validate();
      });
      $this->app->singleton('undown',function(){
          return new  UnDown_validate();
      });
      $this->app->singleton('data_request',function(){
          return new Data_Request();
      });

  }

}
