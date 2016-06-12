<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/11
 * Time: 20:10
 */

namespace geetest;
use Illuminate\Support\Facades\Config;


class Data_Request
{
    public function send_request($url) {


        if (function_exists('curl_exec')) {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, Config::get('geetest.connectTimeout'));
            curl_setopt($curl, CURLOPT_TIMEOUT,  Config::get('geetest.socketTimeout'));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            $data = curl_exec($curl);

            if (curl_errno($curl)) {
                $err = sprintf("curl[%s] error[%s]", $url, curl_errno($curl) . ':' . curl_error($curl));
                trigger_error($err);
            }

            curl_close($curl);
        } else {
            $opts    = array(
                'http' => array(
                    'method'  => "GET",
                    'timeout' =>Config::get('geetest.connectTimeout') + Config::get('geetest.socketTimeout'),
                )
            );
            $context = stream_context_create($opts);
            $data    = file_get_contents($url, false, $context);
        }

        return $data;
    }
    public function post_request($url, $postdata = '') {
        if (!$postdata) {
            throw new Exception("no postdata!!" );
        }

        $data = http_build_query($postdata);
        if (function_exists('curl_exec')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, Config::get('geetest.connectTimeout'));
            curl_setopt($curl, CURLOPT_TIMEOUT, Config::get('geetest.socketTimeout'));


            if (!$postdata) {
                curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            } else {
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            $data = curl_exec($curl);

            if (curl_errno($curl)) {
                $err = sprintf("curl[%s] error[%s]", $url, curl_errno($curl) . ':' . curl_error($curl));
                trigger_error($err);
            }

            curl_close($curl);
        } else {
            if ($postdata) {
                $opts    = array(
                    'http' => array(
                        'method'  => 'POST',
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($data) . "\r\n",
                        'content' => $data,
                        'timeout' => Config::get('geetest.connectTimeout')+ Config::get('geetest.socketTimeout')
                    )
                );
                $context = stream_context_create($opts);
                $data    = file_get_contents($url, false, $context);
            }
        }

        return $data;
    }
}