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


$page_count = $todo->countTodo();

/**
 * return　ページャーの数
 * @param int $page_count
 * @return int $ttl_page
 */
function calc_ttl_page(int $page_count)
{
    // constant is defined at config.php
    $ttl_page = (int) ceil($page_count / per_page);
    return $ttl_page;
}

function pagination($page_count, $now_page = 1)
{



}

pagination($page_count);

echo nl2br("\n");
echo "page count: " . $page_count;

$ttl_page = calc_ttl_page($page_count);

if (!isset($_GET['id']))
{
    $now_page = 1; 
} else {
    $now_page = (int) $_GET['id'];
}

echo nl2br("\n");
echo "now_page:" . $now_page;
echo nl2br("\n");
echo gettype($now_page);
echo nl2br("\n");
echo "ttl_page" . $ttl_page;
echo gettype($ttl_page);

// 現在のページが1のとき、①（現在のページ）が先頭にくるようにする
$prev_page = $now_page === 1 ? null : max($now_page - 1, 1); 

// 現在のページがページの最後のとき、そのページが最後尾にくるようにする。
$next_page = $now_page === $ttl_page ? null : min($now_page + 1, $ttl_page); 

$offset = ($now_page - 1) * 2;

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
    <main data-token="<?= Utils::h($_SESSION['token']) ;?>">
        <h1>RECIPEHOUSE</h1>
        <span class="purge">purge</span>
        <form class="add-form">
        <input type="text" name="title" style="display: block; margin-bottom: 10px">
        <textarea name="content" rows=8 cols=40></textarea>
        <input type="submit" value="add">
        </form>
        <ul>
        <?php foreach($todos as $todo): ?>
            <li data-id="<?= Utils::h($todo->id); ?>">
                <div class="title-container">
                    <input type="checkbox" <?= $todo->is_done ? 'checked' : ''?>>
                    <a href="./single.php?id=<?= Utils::h($todo->id); ?>" class="title"><?= $todo->title; ?></a>
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

        <div class="pagination">
            <?php if ($now_page != 1): ?>
                <a href="index.php?id=<?= $prev_page ?>"><button>prev</button></a>
            <?php endif; ?>
            <a class="prev_page" href="index.php?id=<?= $prev_page ?>"><?= $prev_page ?></a>
            <a class="now_page" href="index.php?id=<?= $now_page ?>"><?= $now_page ?></a>
            <a class="next_page" href="index.php?id=<?= $next_page ?>"><?= $next_page ?></a>
            <?php if ($now_page < $ttl_page): ?>
                <a href="index.php?id=<?= $next_page ?>"><button>next</button></a>
            <?php endif; ?>
        </div>
        <script src="main.js"></script>
    </main>
    </body>
</html>




