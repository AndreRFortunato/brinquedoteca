<?php

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['nome'])) {
    die("Você não pode acessar esta página porque não está logado.<p><a href=\"indexlogin.php\">Entrar</a></p>");
}


?>