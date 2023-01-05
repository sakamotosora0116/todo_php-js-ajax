テーブル構成

main.sql参照

セッション一覧

session.txt参照（まだ未実装）

codeみてておもったんだけど todo.php のupchange もっと短くかけるな。

 $clickTarget = $stm1->fetch(\PDO::FETCH_COLUMN);

    $stm2 = $this->pdo->prepare("SELECT min(pos) FROM todos WHERE pos > (SELECT pos FROM todos WHERE id = :id)");
    
    を
    
    $stm2 = $this->pdo->prepare("SELECT min(pos) FROM todos WHERE pos > $clickTarget");
    
    


When I was reading Todo.php code, I wonder 


開発状況

    if (e.target.parentNode.classList.contains('visible'))
    {
      e.preventDefault();
      const title = e.target.firstElementChild.value;
      fetch('?action=textChange', {
        method: 'POST',
        body: new URLSearchParams({
          title: title,
          id: e.target.parentNode.dataset.id,
          token: token,
        })
      })
      // const aaa = e.target.parentNode.parentNode.childNodes[0].childNodes[1].nodeValue = title;
      // const title = e.target.firstElementChild.value;
      // console.log(aaa);
    }
