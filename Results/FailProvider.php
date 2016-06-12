<?php
namespace geetest\Results;

use geetest\Results\Results_interface;
class FailProvider  implements Results_interface
{
  public function handle($getResults,$ID,$KEY)
  {
    $rnd1           = md5(rand(0, 100));
    $rnd2           = md5(rand(0, 100));
    $challenge      = $rnd1 . substr($rnd2, 0, 2);
    $callback_data  =
    [
      "challenge"   => $challenge,
      "gt"          => $ID,
      "success"     => 0,
    ];
    return $callback_data;
  }
}
 ?>
