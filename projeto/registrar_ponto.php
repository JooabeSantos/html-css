<?php

session_start();//inicial

ob_start();


//horario padrao
date_default_timezone_set('America/Sao_Paulo');


$horario_atual = date("H:i:s");
//var_dump($horario_atual);


//gerar a data 
$data_entrada = date('Y/m/d');

//conectar com banco de dados
include_once "conexao.php";


$id_usuario = 1;

//recuperar o ultimo ponto do usuario

$query_ponto = "SELECT id AS id_ponto, saida_intervalo, retorno_intervalo, saida
            FROM pontos
            WHERE usuario_id = :usuario_id
            ORDER BY id DESC
            LIMIT 1";


$result_ponto = $conn->prepare($query_ponto); 

$result_ponto->bindPAram(':usuario_id', $id_usuario);

$result_ponto->execute();


if(($result_ponto) and ($result_ponto->rowCount() != 0)){
    $row_ponto = $result_ponto->fetch(PDO::FETCH_ASSOC);
    //realizar leitura do registro
    //var_dump($row_ponto);
    //extrair para imprimir atraves do nome da chave no array
    extract($row_ponto);


    //intervalo
    if (($saida_intervalo == "") or ($saida_intervalo == null)){  
        $col_tipo_registro = "saida_intervalo";
        $tipo_registro = "editar";
        $text_tipo_registro = "saída intervalo";
    }elseif(($retorno_intervalo == "") or ($retorno_intervalo == null)){
        //retorno do intervalo
        $col_tipo_registro = "retorno_intervalo";
        $tipo_registro = "editar";
        $text_tipo_registro = "retorno do intervalo";
    }elseif(($saida == "") or ($saida == null)){
        //saida
        $col_tipo_registro= "saida";
        $tipo_registro = "editar";
        $text_tipo_registro = "Horario em que terminou de programar";
    }else{//criar novo registro no BD com o horario de entrada
        //saida
        $tipo_registro = "entrada";
        $text_tipo_registro = "entrada";
    }
}else {
    // Tipo de registro
    $tipo_registro = "entrada";

    // Texto parcial que deve ser apresentado para o usuario
    $text_tipo_registro = "entrada";
}

switch ($tipo_registro) {
        // Acessa o case quando deve editar o registro
    case "editar":
        // Query para editar no banco de dados
        $query_horario = "UPDATE pontos SET $col_tipo_registro =:horario_atual
                    WHERE id=:id
                    LIMIT 1";

        // Preparar a QUERY
        $cad_horario = $conn->prepare($query_horario);

        // Substituir o link da QUERY pelo valor
        $cad_horario->bindParam(':horario_atual', $horario_atual);
        $cad_horario->bindParam(':id', $id_ponto);
        break;
    default:
        // Query para cadastrar no banco de dados
        $query_horario = "INSERT INTO pontos (data_entrada, entrada, usuario_id) VALUES (:data_entrada, :entrada, :usuario_id )";

        // Preparar a QUERY
        $cad_horario = $conn->prepare($query_horario);

        // Substituir o link da QUERY pelo valor
        $cad_horario->bindParam(':data_entrada', $data_entrada);
        $cad_horario->bindParam(':entrada', $horario_atual);
        $cad_horario->bindParam(':usuario_id', $id_usuario);
        break;
}

// Executar a QUERY
$cad_horario->execute();

// Acessa o IF quando cadastrar com sucesso
if ($cad_horario->rowCount()) {
    $_SESSION['msg'] = "<p style='color: green;'>Horário de $text_tipo_registro cadastrado com sucesso!</p>";
    header("Location: index.php");
} else {
    $_SESSION['msg'] = "<p style='color: #f00;'>Horário de $text_tipo_registro não cadastrado com sucesso!</p>";
    header("Location: index.php");
}