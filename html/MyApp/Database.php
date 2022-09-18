<?php

namespace MyApp;


class Database
{

  private static $pdoInstance;

  public static function getInstance()
  {
    try
    {
      self::$pdoInstance = new \PDO(
        DSN,
        DB_USER,
        DB_PASS,
        [
          \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
          \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
          \PDO::ATTR_EMULATE_PREPARES => false,
        ]
      );
      return self::$pdoInstance;

    } catch(\PDOException $e) {
      echo $e -> getMessage();
    }
  }

}





?>