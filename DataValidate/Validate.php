<?php
/**
 * Created by PhpStorm.
 * User: gbf
 * Date: 2016/6/11
 * Time: 10:32
 */

namespace geetest\DataValidate;


interface Validate
{
     function handle($challenge, $validate, $seccode, $user_id = null,$GT_SDK_VERSION);



}