<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/11
 * Time: 19:25
 */

namespace geetest;


class decode
{

    /**
     * 解码随机参数
     *
     * @param $challenge
     * @param $string
     * @return int
     */
    public function decode_response($challenge, $string) {
        if (strlen($string) > 100) {
            return 0;
        }
        $key             = array();
        $chongfu         = array();
        $shuzi           = array("0" => 1, "1" => 2, "2" => 5, "3" => 10, "4" => 50);
        $count           = 0;
        $res             = 0;
        $array_challenge = str_split($challenge);
        $array_value     = str_split($string);
        for ($i = 0; $i < strlen($challenge); $i++) {
            $item = $array_challenge[$i];
            if (in_array($item, $chongfu)) {
                continue;
            } else {
                $value = $shuzi[$count % 5];
                array_push($chongfu, $item);
                $count++;
                $key[$item] = $value;
            }
        }

        for ($j = 0; $j < strlen($string); $j++) {
            $res += $key[$array_value[$j]];
        }
        $res = $res - $this->decodeRandBase($challenge);

        return $res;
    }


    /**
     * @param $x_str
     * @return int
     */
    public function get_x_pos_from_str($x_str) {
        if (strlen($x_str) != 5) {
            return 0;
        }
        $sum_val   = 0;
        $x_pos_sup = 200;
        $sum_val   = base_convert($x_str, 16, 10);
        $result    = $sum_val % $x_pos_sup;
        $result    = ($result < 40) ? 40 : $result;

        return $result;
    }

    /**
     * @param $full_bg_index
     * @param $img_grp_index
     * @return int
     */
    public function get_failback_pic_ans($full_bg_index, $img_grp_index) {
        $full_bg_name = substr(md5($full_bg_index), 0, 9);
        $bg_name      = substr(md5($img_grp_index), 10, 9);

        $answer_decode = "";
        // 通过两个字符串奇数和偶数位拼接产生答案位
        for ($i = 0; $i < 9; $i++) {
            if ($i % 2 == 0) {
                $answer_decode = $answer_decode . $full_bg_name[$i];
            } elseif ($i % 2 == 1) {
                $answer_decode = $answer_decode . $bg_name[$i];
            }
        }
        $x_decode = substr($answer_decode, 4, 5);
        $x_pos    = $this->get_x_pos_from_str($x_decode);

        return $x_pos;
    }

    /**
     * 输入的两位的随机数字,解码出偏移量
     *
     * @param $challenge
     * @return mixed
     */
    public function decodeRandBase($challenge) {
        $base      = substr($challenge, 32, 2);
        $tempArray = array();
        for ($i = 0; $i < strlen($base); $i++) {
            $tempAscii = ord($base[$i]);
            $result    = ($tempAscii > 57) ? ($tempAscii - 87) : ($tempAscii - 48);
            array_push($tempArray, $result);
        }
        $decodeRes = $tempArray['0'] * 36 + $tempArray['1'];

        return $decodeRes;
    }

}