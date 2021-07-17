<?php
    session_start();
    //сессия нужна - не знаю зачем, лишней не будет
    //выходит так, что в результирующую ссылку, укороченную, я записываю хеш в массив GET
    //отсюда и работаю, не судите строго...
    if($_GET['link'] != '')
    {
        $hash_link = $_GET['link'];
        $conn = new PDO("pgsql:dbname=kok;host=localhost", "postgres", "322");
        //отправляю запрос на получение данных: ссылки и её текущеё колво перевоход по хешу и массиве GET
        $s = $conn->prepare("SELECT original_link, using_count FROM koks WHERE hash_link = '$hash_link'");
        $s->execute();
        $count = $s->rowCount();
        if($count != 0)
        {
            $arr = $s->fetch(PDO::FETCH_LAZY);
            $link = $arr[0];
            $counts = $arr[1] + 1;
            //тут получаю и увеличиваю на один, так как следующий шаг, или скорый, приведет к переходу
            $s = $conn->prepare("UPDATE koks SET using_count = $counts WHERE hash_link = '$hash_link'");
            //тут обновил этот параметр и перехожу по ссылке
            $s->execute();
            header("Location: $link");
        }
    }

?>
<html>
    <head>
        <title>Укоротитель ссылок</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="/css/signin.css" rel="stylesheet">
        <script src="/js/jquery-3.6.0.js"></script>
        
        <style>
            body{
                background-color: bisque;
            }
        </style>
    </head>
    <body class="text-center">
        <main class="form-signin">
        <form method="POST" action="short.php" id="id_form">
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="Ссылка" name="link">
                <label for="floatingInput" id="id_label">Ссылка</label>
            </div><br>
        </form>
        <button class="btn btn-lg btn-primary" type="submit" id="id_button">Укоротить ссылку</button>
        </main>
        <script>
            $(document).ready(function(){
                //когда-то научусь обходиться без этого, но пока так
                //раз у меня кнопка не внутри формы, required я поставить не могу, поэтому так
                //могу, конечно, создать невидимую кнопку, но работает через раз
                if($('#floatingInput').val() == '')
                    $('#id_button').prop('disabled', true);
                
                $('#floatingInput').change(function(){
                    if($(this).val() != '')
                        $('#id_button').prop('disabled', false);
                    else
                        $('#id_button').prop('disabled', true);
                })
                //событие на кнопку снизу
                $('#id_button').click(function(){
                    if($(this).text() == 'Скопировать')
                    {
                        $('#floatingInput').select();
                        document.execCommand("copy");
                    }
                    else
                    {
                        $.ajax(
                        {
                            url: 'kok.php',
                            type: "POST",
                            dataType: "html",
                            data: $("#id_form").serialize(),
                            async: false,
                            success: function(response){
                                //тут мы получаем ответ от сервера, проверяем на то что внутри и работаем по сценарию
                                result = $.parseJSON(response);
                                if(result != 'error')
                                {
                                    $('#id_label').text('Ваша короткая ссылка');
                                    $('#floatingInput').prop('placeholder', 'Ваша короткая ссылка')
                                    $('#floatingInput').val(result);
                                    $('#id_button').text('Скопировать');
                                }
                                else
                                {
                                    $('#floatingInput').val(result);
                                }
                            }
                        });
                    }
                })
            })
        </script>
    </body>
</html>