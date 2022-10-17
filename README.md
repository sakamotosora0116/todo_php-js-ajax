codeみてておもったんだけど todo.php のupchange もっと短くかけるな。


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
