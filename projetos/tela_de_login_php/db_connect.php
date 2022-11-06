<?php
//conexao com banco de dados

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistemalogin";

$connect = mysqli_connect($servername, $username, $password, $dbname);

if(mysqli_connect_error()){
    echo "Falha na conxao: ".mysqli_connect_error();
}