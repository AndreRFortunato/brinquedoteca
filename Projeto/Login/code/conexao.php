<?php

$user = 'root';
$password = '';
$database = 'Banco-Php';
$host = 'localhost';

$mysqli = new mysqli($host, $user, $password, $database);
mysqli_set_charset($mysqli,"utf8");
if($mysqli->error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->error);
}