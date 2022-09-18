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

$todo->processPost();
$todos = $todo->getAll();


?>

<!DOCTYPE html>
<html lang=" ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles.css">
  <title>todo</title>
</head>
<body>

    <!-- <div class="ope-container">
      <span class="delete">delete</span>
      <span class="upChange">up↑</span>
      <span class="topChange">topChange</span>
      <span class="downChange">down↓</span>
      <span class="bottomChange">bottomChange</span>
      <span class="textChange">textChange</span>
    </div>
    <form class="invisible-container">
      <textarea cols="17" rows="4" name="title"></textarea>
      <input type="submit" value="submit">
    </form> -->

  <main data-token="<?= Utils::h($_SESSION['token']) ;?>">
    <h1>TODO</h1>
    <span class="purge">purge</span>
    <form class="add-form">
      <input type="text" name="title">
      <input type="submit" value="add">
    </form>
    <ul>
      <?php foreach($todos as $todo): ?>
      <li data-id="<?= Utils::h($todo->id); ?>">
        <div class="title-container">
            <input type="checkbox" <?= $todo->is_done ? 'checked' : ''?>>
            <span class="title"><?= $todo->title;?></span>
        </div>
        <div class="ope-container">
          <span class="delete">delete</span>
          <span class="upChange">up↑</span>
          <span class="topChange">topChange</span>
          <span class="downChange">down↓</span>
          <span class="bottomChange">bottomChange</span>
          <span class="textChange">textChange</span>
        </div>
        <form class="invisible-form">
          <textarea cols="17" rows="4" name="title"></textarea>
          <input class="change-input" type="submit" value="change">
        </form>
      </li>
      <?php endforeach; ?>
    </ul>
    <script src="main.js"></script>
  </main>
</body>
</html>




