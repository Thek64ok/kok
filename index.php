<?php
    session_start();
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
                <label for="floatingInput">Ссылка</label>
            </div><br>
        </form>
        <button class="btn btn-lg btn-primary" type="submit" id="id_button">Укоротить ссылку</button>
        </main>
        <script>
            $(document).ready(function(){
                if($('#floatingInput').val() == '')
                    $('#id_button').prop('disabled', true);
                
                $('#floatingInput').change(function(){
                    if($(this).val() != '')
                        $('#id_button').prop('disabled', false);
                    else
                        $('#id_button').prop('disabled', true);
                })

                $('#id_button').click(function(){
                    $.ajax(
                    {
                        url: 'kok.php',
                        type: "POST",
                        dataType: "html",
                        data: $("#id_form").serialize(),
                        async: false,
                        success: function(response){
                            result = $.parseJSON(response);
                            console.log(result);
                        }
                    });
                })
            })
        </script>
    </body>
</html>