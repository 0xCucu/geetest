<?php
namespace geetest\Results;

use geetest\Results\Results_interface;
class SuccessProvider implements Results_interface
{
  public function handle($getResults,$ID,$KEY)
  {
    $callback_data  =
    [
      "challenge"   => md5($getResults.$KEY),
      "gt"          => $ID,
      "success"     => 1,
    ];
    return $callback_data;
  }
}
 ?>
