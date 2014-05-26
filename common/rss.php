<?php

    // using for downloading rss
    if(empty($_GET["q"])){
        // forbidden status code
        http_response_code(403);
    }else{
        $x = simplexml_load_file($_GET["q"]);
        echo json_encode($x, true);
    }