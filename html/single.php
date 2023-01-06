<?php

require_once('./MyApp/config.php');
require_once('./MyApp/Database.php');
require_once('./MyApp/Todo.php');
require_once('./MyApp/Token.php');
require_once('./MyApp/Utils.php');

use MyApp\Token;
use MyApp\Todo;
use MyApp\Utils;
use MyApp\Database;

$pdo = Database::getInstance();

$todo = new Todo($pdo);

$pageNum = (int) $_GET['id'];

echo $pageNum;



?>