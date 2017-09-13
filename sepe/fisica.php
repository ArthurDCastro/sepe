<?php
    require "logica/lib.php";

    if (!isset($_GET["acao"])){
        $_GET["acao"] = "variaveis";
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta charset="UTF-8">

</head>
<body background="img/fundo.jpg">
<style>
    @font-face {
        font-family: airbus;
        src: url(fontes/airbus.ttf);
    }
</style>

<div class="row" style="border-radius: 30px; margin: 30px auto; background-color:rgba(300, 300, 300, 0.8); width: 850px">
    <h2 align="center" style="color: black;font-size: 56px; font-family: 'airbus'"> FÍSICA !</h2>
</div>
<div class="container">
    <div class="row" style="border-radius: 30px; margin: auto; background-color:rgba(50, 50, 50, 0.9);width: 850px">
        <div class="col-md-12">
            <div class="col-md-12" style="color: white">
                <?php
                switch ($_GET["acao"]) {
                    case "variaveis":
                        include "arquivosPadrao/formVariaveis.php";
                        break;

                    case "dados":
                        include "arquivosPadrao/formDados.php";
                        break;

                    case "resultado":
                        include "arquivosPadrao/resultado.php";
                        break;

                    default:
                        break;
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>