<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hora";

try {
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    echo "Conexão com banco de dados realizado com sucesso!";
} catch (PDOException $err){
    echo "Erro: Conexção com banco de dados não realizado. erro gerado" . $err->getMessage();
}