<?php


namespace MyApp;

class Token
{
  public static function create()
  {
    if (!isset($_SESSION['token']))
    {
      $_SESSION['token'] = bin2hex(random_bytes(32));
    }
  }
  
  public static function validate()
  {
    if ($_SESSION['token'] !== filter_input(INPUT_POST, 'token'))
    {
      exit('INVALID post request');
    }
  }

}


?>