<?php
// Conexao
require_once 'db_connect.php';

session_start();



//botao enviar
if(isset($_POST['btn-entrar'])){
    $erros = array();
    $login = mysqli_escape_string($connect, $_POST['login']);
    $senha = mysqli_escape_string($connect, $_POST['senha']);

    //
    if(empty($login) or empty($senha)){
        $erros[] = "<li> O campo login/senha precisa ser preenchido</li>";
    }else{
        $sql = "SELECT login FROM usuarios WHERE login = '$login'";
        $resultado = mysqli_query($connect, $sql);

        //Ver se o usuario está no banco de dados
        if(mysqli_num_rows($resultado) > 0){
            $senha = md5($senha);
            $sql = "SELECT * FROM usuarios WHERE login = '$login' AND senha = '$senha'";
            $resultado = mysqli_query($connect, $sql);
                if(mysqli_num_rows($resultado) == 1){
                    $dados = mysqli_fetch_array($resultado);
                    mysqli_close($connect);
                    $_SESSION['logado'] = true;
                    $_SESSION['id_usuario'] = $dados['id'];
                    header('Location: home.php');
                }else {
                    $erros[] = "<li> Usuario e senha não encontrado </li>";
                }
          
        } else{
            $erros[] = "<li> Usuario inexistente </li>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    
    <div class="main-login">
        <div class="left-login">
            <h1>Login</h1>
            <img src="money.svg" class="left-img-money" alt="Estudando">
        </div>
        <div class="right-login">
            <div class="card-login">
                <?php
                if(!empty($erros)){
                    foreach($erros as $erro){
                        echo "<span>$erro </span>";
                    }
                }
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="textfield">
                        <label for="login">Usuario</label>
                        <input type="text" name="login"><br>
                    </div>
                    <div class="textfield">
                        <label for="senha">Senha</label>
                        <input type="password" name="senha" id="isenha"><br>
                    </div>
                    <button class="btn-login" type="submit" name="btn-entrar">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>