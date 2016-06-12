<?php
/**
 * Created by PhpStorm.
 * User: gbf
 * Date: 2016/6/11
 * Time: 10:31
 */

namespace geetest\DataValidate;

use geetest\DataValidate\Validate;
use App;
use Illuminate\Support\Facades\Config;

class UnDown_validate implements  Validate
{
    public function handle($challenge, $validate, $seccode, $user_id = null,$GT_SDK_VERSION)
    {
       if(!$this->checkValidate($challenge, $validate) ){
           return false;
       }
        $data = array(
            "seccode" => $seccode,
            "sdk"     => $GT_SDK_VERSION,
        );
        $user_id = Config::get('geetest.user_id') ;
        if ((!$user_id = null) and (is_string($user_id))) {
            $data["user_id"] = $user_id;
        }
        $url          = "http://api.geetest.com/validate.php";
        $data_request = App::make('data_request');

        $codevalidate = $data_request->post_request($url, $data);
        if ($codevalidate == md5($seccode)) {
            return 1;
        } else {
            if ($codevalidate == "false") {
                return 0;
            } else {
                return 0;
            }
        }

    }
    public function checkValidate($challenge, $validate)
    {
        if (strlen($validate) != 32 && md5( Config::get('geetest.KEY') . 'geetest' . $challenge) != $validate) {
            return false;
        }


        return true;
    }





}