'use strict';

{

    const token = document.querySelector('main').dataset.token;
    const input_title = document.querySelector('[name="title"]')
    const textarea_content = document.querySelector('textarea')
    const ul = document.querySelector('ul');
    // const textChangeBoxes = document.querySelectorAll('.textChange')

    /**
     * for ajax, create a todo and put it in ul tag
     * @param {int} id 
     * @param {string} titleValue 
     */
    function addTodo(id, titleValue) {

        const li = document.createElement('li')
        li.dataset.id = id;

        const titleContainer = document.createElement('div')
        titleContainer.classList.add('title-container')


        const opeContainer = document.createElement('div')
        opeContainer.classList.add('ope-container')

        const checkbox = document.createElement('input')
        checkbox.type = 'checkbox'


        const title = document.createElement('span');
        title.textContent = titleValue;

        const deleteSpan = document.createElement('span');
        deleteSpan.textContent = 'delete';
        deleteSpan.classList.add('delete');


        const upChange = document.createElement('span');
        upChange.textContent = 'up↑';
        upChange.classList.add('upChange');

        const topChange = document.createElement('span');
        topChange.textContent = 'topChange';
        topChange.classList.add('topChange');


        const downChange = document.createElement('span');
        downChange.textContent = 'down↓';
        downChange.classList.add('downChange');


        const bottomChange = document.createElement('span');
        bottomChange.textContent = 'bottomChange';
        bottomChange.classList.add('bottomChange');


        const textChange = document.createElement('span');
        textChange.textContent = 'textChange';
        textChange.classList.add('textChange');

        const invContainer = document.createElement('form');
        invContainer.classList.add('invisible-form');
        invContainer.method = 'POST';
        invContainer.action = '?action=textChange';

        const textArea = document.createElement('textarea');
        textArea.cols = 17;
        textArea.rows = 4;
        textArea.name = 'title';

        const textInput = document.createElement('input');
        textInput.type = 'submit';
        textInput.value = "change";
        textInput.classList.add('change-input');

        invContainer.appendChild(textArea);
        invContainer.appendChild(textInput);

        li.appendChild(titleContainer);
        titleContainer.appendChild(checkbox);
        titleContainer.appendChild(title);

        li.appendChild(opeContainer);
        opeContainer.appendChild(deleteSpan);
        opeContainer.appendChild(upChange);
        opeContainer.appendChild(topChange);
        opeContainer.appendChild(downChange);
        opeContainer.appendChild(bottomChange);
        opeContainer.appendChild(textChange);

        li.appendChild(invContainer);

        const ul = document.querySelector('ul');
        ul.insertBefore(li, ul.firstChild);

    }

    ul.addEventListener('click', e => {
        if (e.target.type === 'checkbox') {
            fetch('?action=toggle', {
                method: 'POST',
                body: new URLSearchParams({
                    id: e.target.parentNode.parentNode.dataset.id,
                    token: token,
                }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('this todo has been deleted');
                }

                return response.json();
            })

            .then(json => {
                if(json.is_done !== e.target.checked) {
                    alert('This todo has been updated. UI is being updated');
                    e.target.checked = json.is_done;
                }
            })
            .catch(err => {
                alert(err.message);
                location.reload();
            })
        }

        if (e.target.classList.contains('delete')) {
            fetch('?action=delete', {
                method: 'POST',
                body: new URLSearchParams({
                    id: e.target.parentNode.parentNode.dataset.id,
                    token: token,
                })
            });
            e.target.parentNode.parentNode.remove();
        }


        if (e.target.classList.contains('upChange')) {
            fetch('?action=upChange', {
                method: 'POST',
                body: new URLSearchParams({
                    id: e.target.parentNode.parentNode.dataset.id,
                    token: token,
                })
            });
            const preTarget = e.target.parentNode.parentNode.previousElementSibling;
            const moveTarget = e.target.parentNode.parentNode;

            // if a clicked todo is not at the top
            if (moveTarget !== ul.children[0]) {
                ul.insertBefore(moveTarget, preTarget);
            }
        }


        if (e.target.classList.contains('topChange')) {
            fetch('?action=topChange', {
                method: 'POST',
                body: new URLSearchParams({
                    id: e.target.parentNode.parentNode.dataset.id,
                    token: token,
                })
            });
            const Target = ul.firstElementChild;
            const moveTarget = e.target.parentNode.parentNode;

            if (moveTarget !== ul.children[0]) {
            ul.insertBefore(moveTarget, Target);
            }
        }


    if (e.target.classList.contains('downChange')) {
        fetch('?action=downChange', {
        method: 'POST',
        body: new URLSearchParams({
            id: e.target.parentNode.parentNode.dataset.id,
            token: token,
        })
        });
        const postTarget = e.target.parentNode.parentNode.nextElementSibling;
        const moveTarget = e.target.parentNode.parentNode;

        if (moveTarget !== ul.lastElementChild) {
        ul.insertBefore(postTarget, moveTarget);
        }
    }


        if (e.target.classList.contains('bottomChange')) {
            fetch('?action=bottomChange', {
            method: 'POST',
            body: new URLSearchParams({
                id: e.target.parentNode.parentNode.dataset.id,
                token: token,
            })
            });
            const moveTarget = e.target.parentNode.parentNode;

            if (moveTarget !== ul.lastElementChild) {
            ul.appendChild(moveTarget);
            }
        }


        /* change todo's text */

        if (e.target.classList.contains('textChange')) {

            const forms = document.querySelectorAll('.invisible-form');

            forms.forEach(form => {
                if (form.classList.contains('visible') && !(e.target.parentNode.nextElementSibling.classList.contains('visible')))
                {
                    form.classList.remove('visible');
                }
            })
            e.target.parentNode.nextElementSibling.classList.toggle('visible');
        }


    });

    ul.addEventListener('submit', e => {
        e.preventDefault();

        // console.log(e.target);

        if (e.target.classList.contains('visible'))
        {
            const title = e.target.firstElementChild.value;
            fetch('?action=textChange', {
                method: 'POST',
                body: new URLSearchParams({
                    title: title,
                    id: e.target.parentNode.dataset.id,
                    token: token,
                })
            })
            e.target.parentNode.firstElementChild.lastElementChild.textContent = title;
        }

    });

  /* add a task to ul */

    document.querySelector('.add-form').addEventListener('submit', e => {
        e.preventDefault();

        const title = input_title.value;
        const content = textarea_content.value;

        fetch('?action=add', {
        method: 'POST',
        body: new URLSearchParams({
            title: title,
            content: content,
            token: token,
        }),
        })
        // .then(response => {
        //   return response.json();
        // })

        .then(response => response.json())
        .then(json => {
            addTodo(json.id, title);
        });

        input_title.value = '';
        input_title.focus();
    })

    /* purge task */

    const purge = document.querySelector('.purge');
    purge.addEventListener('click', () => {
        fetch('?action=purge', {
            method: 'POST',
            body: new URLSearchParams({
                token: token,
            })
        });

        const lis = document.querySelectorAll('li');
        lis.forEach(li => {
            if (li.children[0].children[0].checked) {
                li.remove();
        }
        });
    });

}