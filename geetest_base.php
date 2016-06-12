<?php
namespace geetest;

use geetest\Results\Fail;

use geetest\Results\SuccessProvider;
use Illuminate\Support\Facades\Config;
use Closure;
use Exception;
use Illuminate\Http\Request;
use geetest\DataValidate\UnDown_validate;
use geetest\DataValidate\Down_validate;
use App;
use Session;

class geetest_base
{
    const GT_SDK_VERSION = 'php_3.2.0';

    protected $ID;
    protected $KEY;
    protected $response_data;
    protected $check_results;
    protected $server_status;
    protected $ifDown;
    protected $api_url;
    /**
     *
     * 初始化包含第一次和第二次认证
     * @param null $user_id
     * @return first:json or second:project
     */
    public function init(Request $request,Closure $Success_action = null,Closure $Fail_action = null)
    {

        $this->ID = Config::get('geetest.ID');
        $this->KEY = Config::get('geetest.KEY');
        //判断是否是第一次验证
        if ( $request->isMethod('get') ){

            return $this->checkIfDwon(
                function($getResults)use ($Success_action,$Fail_action){

                    $check_results = $this->check_results;
                    $Provider=__NAMESPACE__.'\\Results\\'.$check_results."Provider";
                    $check_results_provider = new $Provider ();
                    if ( method_exists( $check_results_provider , 'handle')){
                        //调用数据提供者
                        $data = call_user_func_array(array($check_results_provider ,"handle") , array($getResults ,$this->ID ,$this->KEY) );
                        return $this->first_Response($data);//响应参数
                    }else{
                        throw new Exception("geetest Results Provider '{$check_results}' not find" );
                    }
                }
            );

        }elseif ($request->isMethod('post')){
            //判断是否验证成功
            if( Session::get('status') ){
                $this->ifDown =   App::make('undown');
            }else{
                $this->ifDown =  App::make('down');
            }
            $res=call_user_func_array([ $this->ifDown,'handle'],[$request->input('geetest_challenge'),$request->input('geetest_validate'), $request->input('geetest_seccode'),Config::get('geetest.user_id'),self::GT_SDK_VERSION]);
            if($res){
                $Success_action();
            }else{
                $Fail_action();
            }

        }
    }
    /**
     * 判断极验服务器是否down
     *
     *
     * @return project
     */
    public function checkIfDwon(Closure $check_results_action)
    {
        $checkApi = $this->parseApiUrl();
        $data_request = App::make('data_request');
        $getResults = $data_request->send_request($checkApi);
        if (strlen($getResults) != 32) {
            $this->check_results = preg_replace('/\s/',' ',Config::get('geetest.Fail_callback'));
            Session::put('status',false);
        }else{
            $this->check_results =  preg_replace('/\s/',' ',Config::get('geetest.Success_callback'));
            Session::put('status',true);
        }

        return $check_results_action($getResults);

    }
    public function parseApiUrl()
    {
        $api_url = Config::get('geetest.api_url');

        $api_url = preg_replace('/\s/','',$api_url);
        preg_match_all('/\{([\s\S]*?)\}/',$api_url,$va);
        $this->api_url=$api_url;
        foreach ($va[1] as $value)
        {

            $this->api_url = preg_replace('/{'.$value.'}/',Config::get('geetest.'.$value),$this->api_url);
        }
        return $this->api_url ;

    }
    /**
     * 响应数据
     */
    public function first_Response(array $data){
        return response()->json($data);


    }


}


?>
