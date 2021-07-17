<?php
    session_start();
    $full_link = $_POST['link'];
    $valid = http_response($full_link);
    if($valid)
    {
        $conn = new PDO("pgsql:dbname=kok;host=localhost", "postgres", "322");
        $s = $conn->prepare("SELECT * FROM koks WHERE original_link = '$full_link'");
        $s->execute();
        $count = $s->rowCount();
        if($count != 0)
        {
            $short_link = $s->fetch(PDO::FETCH_LAZY);
            $message = $short_link[2];
        }
        else
        {
            $s = $conn->prepare("SELECT max(id_link) FROM koks");
            $s->execute();
            $main_id = $s->fetch(PDO::FETCH_LAZY);
            $id = $main_id[0];
            $hash = mb_substr(md5($full_link), 0, 7);
            $short_link = "kok.com/$hash";
            if(is_null($id))
            {
                $s = $conn->prepare("INSERT INTO koks VALUES(1, '$full_link', '$short_link', '$hash')");
            }
            else
                $s = $conn->prepare("INSERT INTO koks VALUES($id + 1, '$full_link', '$short_link', '$hash')");
            $s->execute();
            $message = $short_link;
        }
    }
    else
    {
        $message = 'error';
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