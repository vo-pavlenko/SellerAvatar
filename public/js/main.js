let openModal = document.querySelectorAll('tr[id]');
let closeModal = document.querySelector('.modal_close img');
let mainBlock = document.querySelector('.content');
let modalBLock = document.querySelector('.modal');
let addBtn = document.querySelector('.btn_add');
let saveBtn = document.querySelector('.btn_save');
let sumNumInput = 0;
let textDataInput = '';
let id = '';

openModal.forEach(tr => {
    tr.addEventListener('click', e => {
        id = tr.id;
        mainBlock.style.display = 'none';
        modalBLock.style.display = 'block';
        let modalTable = modalBLock.querySelector('.modal_table');
        modalTable.children[0].innerText = tr.children[0].innerText;
    });
});

closeModal.addEventListener('click', e => {
    mainBlock.style.display = 'flex';
    modalBLock.style.display = 'none';

    let modalTable = modalBLock.querySelector('tbody');

    if (modalTable) {
        modalTable.remove();
    }   
});

addBtn.addEventListener('click', e => {
    let modalTable = modalBLock.querySelector('.modal_table');

    if (!modalTable.children[1]) {
        let addTBody = document.createElement('tbody');

        addTBody.classList.add('item');
        modalTable.appendChild(addTBody);
    }

    let tBodyBlock = modalTable.children[1];
    let html = '';

    if (tBodyBlock.children.length %2 == 0) {
        html = getBlockHtml('Введите слово', 'text');
    } else {
        html = getBlockHtml('Введите число', 'num');
    }

    tBodyBlock.innerHTML += html;

    let numInput = document.querySelectorAll('input[name="num"]');
    let textInput = document.querySelectorAll('input[name="text"]');
    let delLine = document.querySelectorAll('.del_line');

    numInput.forEach(input => {
        input.addEventListener("keypress", evt => {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var regex = /[0-9]/;

            if( !regex.test(key) || (key === '0' && input.value.length === 0)) {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        });
        input.addEventListener("blur", e => {
            sumNumInput = 0;
            let modalCount = modalBLock.querySelector('.modal_count');

            for (var i = 0; i < numInput.length; i++) {
                sumNumInput += Number(numInput[i].value);
            }
            
            input.setAttribute('value', input.value);
            modalCount.innerText = "Общая сумма чисел: " + sumNumInput;
        });
    });

    textInput.forEach(input => {
        input.addEventListener("keypress", validateText);
        input.addEventListener("blur", e => {
            input.setAttribute('value', input.value);

            textDataInput = '';
            for (var i = 0; i < textInput.length; i++) {
                if (textInput[i].value != '') {
                    textDataInput += ' ' + textInput[i].value + ';';
                }
            }
        });
    });

    delLine.forEach(del => {
    del.addEventListener('click', e => {
        del.parentNode.parentNode.parentNode.removeChild(del.parentNode.parentNode);
        let modalTable = modalBLock.querySelector('tbody');

        if (!modalTable.children[0]) {
            modalTable.remove();
        }   
    });
});
});

saveBtn.addEventListener('click', e => {

    let url = `/action/action_save_data.php`;
    url += `?id=${id}`;
    url += `&text=${textDataInput}`;
    url += `&sum=${sumNumInput}`;

    let xhr = new XMLHttpRequest();
    xhr.open('GET', url);
    xhr.responseType = 'json';
    xhr.onload = function (){
        if(xhr.response.status){
            alert('OK');
        }
        else{
            alert('Error!');
        }
    };
    xhr.onerror = function (){
        console.log('XHR.connection.error!');
        alert('Connection error!');
    };
    xhr.send();
});

function getBlockHtml(data, text = false) {
    return `
        <tr class='modal_line'> 
            <td>${data}</td> 
            <td><input type="text" name="${text}" value="" /></td> 
            <td><img class="del_line" src="public/img/close.png"></td>
        </tr>        
    `;
}

function validateText(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[a-zA-Zа-яА-Я]/ui;

    if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
}