<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/11
 * Time: 19:32
 */

namespace geetest\DataValidate;

use geetest\DataValidate\Validate;

use Illuminate\Support\Facades\Config;
use App;
class Down_validate implements  Validate
{

    public function  handle($challenge, $validate, $seccode, $user_id = null,$GT_SDK_VERSION)
    {
        $decode = App::make('geetest_decode');

        if ($validate) {
            $value   = explode("_", $validate);
            $ans     = $decode->decode_response($challenge, $value['0']);
            $bg_idx  = $decode->decode_response($challenge, $value['1']);
            $grp_idx = $decode->decode_response($challenge, $value['2']);
            $x_pos   = $decode->get_failback_pic_ans($bg_idx, $grp_idx);
            $answer  = abs($ans - $x_pos);
            if ($answer < 4) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }


}