<!doctype html>
<html lang="ru">
<head>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css">
</head>
<body>
    <div class="container">
        <section class="content">
            <table class="main_table">
                <caption class="main_caption">Таблица введенных данных</caption>
                <tr class='item_tr'>
                    <th>Имя</th>
                    <th>Cлова</th>
                    <th>Сумма</th>
                </tr>
                <?php 
                    $html = '';
                    foreach ($data as $value) {
                        $html = "
                            <tr class='item_tr_data' id={$value['id']}> 
                                <td>{$value['name']}</td> 
                                <td>{$value['text']}</td> 
                                <td>{$value['sum']}</td> 
                            </tr>
                        ";
                        echo $html;
                    }  
                ?>
            </table>
        </section>     

        <section class="modal">
            <div class="content_modal">
                <div class="modal_close">
                    <img src="public/img/close.png">
                </div>

                <table class="modal_table">
                    <caption class="modal_caption"></caption>
                </table>
                <div class="modal_count"></div>
                <div class="modal_btn">
                    <button class="btn_add">Add row</button>
                    <button class="btn_save">Save</button>
                </div>
            </div> 
        </section>
    </div>

    <script src="/public/js/main.js"></script>
</body>
</html>
