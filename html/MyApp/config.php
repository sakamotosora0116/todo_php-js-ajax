<?php

  session_start();

  define('DSN', 'mysql:host=mysql;dbname=test;charset=utf8mb4');
  define('DB_USER', 'test');
  define('DB_PASS', 'test');

  define('per_page', 3);

  // spl_autoload_register(function($class) {
  //   $prefix = "MyApp\\";
  //   $preFileName = trim($class, $prefix);

  //   $fileName = 'var/www/html/MyApp/' . $preFileName . '.php';

  //   require($fileName);
  // });