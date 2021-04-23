<?php
function connect() //connecte à la base de donnée escape_game

{
    try
    {
        $BDD = new PDO("mysql:host=localhost;dbname=escape_game;charset=utf8", "root", "",
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (Exception $e) {
        die('Erreur fatale :' . $e->getMessage());
    }
    return $BDD;
}

function redirection($url) //redirige vers la page

{
    if (headers_sent()) {
        print('<meta http-equiv="refresh" content ="0;URL=' . $url . '">');
    } else {
        header("Location : $url");
    }
}

function escape($valeur) //permet d'échapper les injections html dans le code

{
    return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8', false);
}
