<?php
    session_start();
    //проверка 1
    $full_link = $_POST['link'];
    if(mb_substr($full_link, 0, 3) == 'kok')
    {
        $message = 'error';
    }
    //проверка 1
    else
    {
        //проверка 2
        $valid = http_response($full_link);
        //проверка 2, дальше сраборает один из вариантов
        if($valid)
        {
            $conn = new PDO("pgsql:dbname=kok;host=localhost", "postgres", "322");
            $s = $conn->prepare("SELECT * FROM koks WHERE original_link = '$full_link'");
            $s->execute();
            $count = $s->rowCount();
            //проверка 3
            if($count != 0)
            {
                $short_link = $s->fetch(PDO::FETCH_LAZY);
                $message = $short_link[2];
            }
            //проверка 3, опять таки, либо создать, либо даст уже существующую укороченную ссылку
            else
            {
                $s = $conn->prepare("SELECT max(id_link) FROM koks");
                $s->execute();
                $main_id = $s->fetch(PDO::FETCH_LAZY);
                $id = $main_id[0];
                $hash = mb_substr(md5($full_link), 0, 7);
                //ссылку я получаю укороченную из хеша и записываю в массив GET, грубо говоря
                $short_link = "kok.com/?link=$hash";
                if(is_null($id))
                {
                    $s = $conn->prepare("INSERT INTO koks VALUES(1, '$full_link', '$short_link', '$hash', 0)");
                    //соответственно, если записей вообще нету, то первый параметр равен 1
                    //эта проверка срабатывает лишь в одном единственном случае
                }
                else
                    $s = $conn->prepare("INSERT INTO koks VALUES($id + 1, '$full_link', '$short_link', '$hash', 0)");
                $s->execute();
                $message = $short_link;
                //по итогу получить переменную $message - там либо error, либо короткая ссылка, все это в ajax формах отдыхает
            }
        }
        else
        {
            $message = 'error';
        }
    }
    echo json_encode($message);

    function http_response($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if(!$head)
            return false;
        if($httpCode < 400)
            return true;
        else
            return false;
    }