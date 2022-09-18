<?php

namespace MyApp;

class Todo
{
  private $pdo;

  function __construct($pdo)
  {
    $this->pdo = $pdo;
    Token::create();
  }

  public function processPost()
  {
    $action = filter_input(INPUT_GET, 'action');

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      Token::validate();
      switch ($action) {
        case 'add':
          $id = $this->add();
          header('Content-Type: application/json');
          echo json_encode(['id' => $id]);
          break;
        case 'delete':
          $this->delete();
          break;
        case 'toggle':
          $isDone = $this->toggle();
          header('Content-type: application/json');
          echo json_encode(['is_done' => $isDone]);
          break;
        case 'downChange':
          $this->downChange();
          break;
        case 'bottomChange':
          $this->bottomChange();
          break;
        case 'upChange':
          $this->upChange();
          break;
        case 'topChange':
          $this->topChange();
          break;
        case 'textChange':
          $this->textChange();
          break;
        case 'purge':
          $this->purge();
          break;
        default:
          exit;
      }
        exit;
    }
  }

  private function add()
  {
    $title = filter_input(INPUT_POST, 'title');
    $stmt1 = $this->pdo->prepare("INSERT INTO todos (title) VALUES (:title)");
    $stmt1->bindValue('title', $title);
    $stmt1->execute();

    $lastId = (int) $this->pdo->lastInsertId();

    $stmt2 = $this->pdo->prepare("UPDATE todos SET pos = id +100 WHERE title = :title");
    $stmt2->bindValue('title', $title);
    $stmt2->execute();

    return $lastId;
  }

  private function delete()
  {
    $id = filter_input(INPUT_POST, 'id');

    $stmt1 = $this->pdo->prepare("DELETE FROM todos WHERE id = (:id)");
    $stmt1->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt1->execute();
  }

  private function toggle()
  {
    $id = filter_input(INPUT_POST, 'id');
    if (empty($id)) {
      return;
    }

    $stmt1 = $this->pdo->prepare("SELECT * FROM todos WHERE id = :id");
    $stmt1->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt1->execute();
    $todo = $stmt1->fetch();
    if (empty($todo)) {
      header('HTTP', true, 404);
      exit;
    }

    $stmt2 = $this->pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = :id");
    $stmt2->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt2->execute();


    return (boolean) !$todo->is_done;
  }

  private function downChange()
  {
    $id = filter_input(INPUT_POST, 'id');

    $stm1 = $this->pdo->prepare("SELECT pos FROM todos WHERE id = :id");
    $stm1->bindValue('id', $id, \PDO::PARAM_INT);
    $stm1->execute();

    $clickTarget = $stm1->fetch(\PDO::FETCH_COLUMN);

    $stm2 = $this->pdo->prepare("SELECT MAX(pos) FROM todos WHERE pos < :clickTarget");
    $stm2->bindValue('clickTarget', $clickTarget, \PDO::PARAM_INT);
    $stm2->execute();
    $changeTarget = $stm2->fetch(\PDO::FETCH_COLUMN);

    if ($changeTarget == null) {
      return;
    }

    $stm4 = $this->pdo->prepare("UPDATE todos SET pos = :clickTarget  WHERE pos = :changeTarget");
    $stm4->bindValue('clickTarget', $clickTarget, \PDO::PARAM_INT);
    $stm4->bindValue('changeTarget', $changeTarget, \PDO::PARAM_INT);
    $stm4->execute();

    $stm3 = $this->pdo->prepare("UPDATE todos SET pos = :changeTarget  WHERE id = :id");
    $stm3->bindValue('changeTarget', $changeTarget, \PDO::PARAM_INT);
    $stm3->bindValue('id', $id, \PDO::PARAM_INT);
    $stm3->execute();


  }

  private function bottomChange()
  {
    $id = filter_input(INPUT_POST, 'id');

    $stm1 = $this->pdo->prepare("SELECT pos FROM todos WHERE id = :id");
    $stm1->bindValue('id', $id, \PDO::PARAM_INT);
    $stm1->execute();

    $clickTarget = $stm1->fetch(\PDO::FETCH_COLUMN);

    $stm2 = $this->pdo->query("SELECT min(pos) -1 FROM todos");
    $changeTarget = $stm2->fetch(\PDO::FETCH_COLUMN);

    if ($clickTarget - 1 == $changeTarget) {
      return;
    }

    $stm4 = $this->pdo->prepare("UPDATE todos SET pos = :changeTarget WHERE id = :id");
    $stm4->bindValue('id', $id, \PDO::PARAM_INT);
    $stm4->bindValue('changeTarget', $changeTarget, \PDO::PARAM_INT);
    $stm4->execute();
  }

  private function upChange()
  {
    $id = filter_input(INPUT_POST, 'id');

    $stm1 = $this->pdo->prepare("SELECT pos FROM todos WHERE id = :id");
    $stm1->bindValue('id', $id, \PDO::PARAM_INT);
    $stm1->execute();

    $clickTarget = $stm1->fetch(\PDO::FETCH_COLUMN);

    $stm2 = $this->pdo->prepare("SELECT min(pos) FROM todos WHERE pos > (SELECT pos FROM todos WHERE id = :id)");
    $stm2->bindValue('id', $id, \PDO::PARAM_INT);
    $stm2->execute();
    $changeTarget = $stm2->fetch(\PDO::FETCH_COLUMN);

    if ($changeTarget == null) {
      return;
    }

    $stm5 = $this->pdo->prepare("UPDATE todos SET pos = $clickTarget WHERE pos = :changeTarget");
    $stm5->bindValue('changeTarget', $changeTarget, \PDO::PARAM_INT);
    $stm5->execute();

    $stm4 = $this->pdo->prepare("UPDATE todos SET pos = $changeTarget WHERE id = :id");
    $stm4->bindValue('id', $id, \PDO::PARAM_INT);
    $stm4->execute();


  }

  private function topChange()
  {
    $id = filter_input(INPUT_POST, 'id');

    $stm1 = $this->pdo->prepare("SELECT pos FROM todos WHERE id = :id");
    $stm1->bindValue('id', $id, \PDO::PARAM_INT);
    $stm1->execute();

    $clickTarget = $stm1->fetch(\PDO::FETCH_COLUMN);

    $stm2 = $this->pdo->query("SELECT max(pos) + 1 FROM todos");
    $changeTarget = $stm2->fetch(\PDO::FETCH_COLUMN);

    if ($clickTarget + 1 == $changePos) {
      return;
    }

    $stm4 = $this->pdo->prepare("UPDATE todos SET pos = :changeTarget WHERE id = :id");
    $stm4->bindValue('id', $id, \PDO::PARAM_INT);
    $stm4->bindValue('changeTarget', $changeTarget, \PDO::PARAM_INT);
    $stm4->execute();
  }

  private function textChange()
  {
    $id = filter_input(INPUT_POST, 'id');
    $title = filter_input(INPUT_POST, 'title');

    $stmt = $this->pdo->prepare("UPDATE todos SET title = :title WHERE id = :id");
    $stmt->bindValue('id', $id, \PDO::PARAM_INT);
    $stmt->bindValue('title', $title);
    $stmt->execute();
  }


  private function purge()
  {
    $this->pdo->query("DELETE FROM todos WHERE is_done = 1");
  }

  public function getAll()
  {
    $stmt = $this->pdo->query("SELECT * FROM todos ORDER BY pos DESC");
    $todos = $stmt->fetchAll();
    return $todos;
  }
}

?>