<?php
    session_start();
    session_destroy();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <title>Calculator 2000</title>
</head>
<body background="img/fundo.jpg">
<style>
    @font-face {
        font-family: airbus;
        src: url(fontes/airbus.ttf);
    }
</style>
<div class="row" style="border-radius: 30px; margin: 30px auto; background-color:rgba(300, 300, 300, 0.8); width: 850px">
    <h2 align="center" style="color: black;font-size: 56px; font-family: 'airbus'">CALCULATOR 2000!</h2>
</div>
    <div class="container">
        <div class="row" style="border-radius: 30px; margin: auto; background-color:rgba(50, 50, 50, 0.9);width: 850px">
            <div class="col-md-12">
                <div class="col-md-4">
                    <a href="conta.php?materia=fisica">
                    <img src="img/fisica-01.jpg" class="img-responsive" style="margin-top: 23px; margin-bottom: 23px; border-radius: 30px">
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="conta.php?materia=matematica">
                    <img src="img/matematica-02.jpg" class="img-responsive" style="margin-top: 23px; margin-bottom: 23px; border-radius: 30px">
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="conta.php?materia=quimica">
                    <img src="img/quimica-03.jpg" class="img-responsive" style="margin-top: 23px; margin-bottom: 23px; border-radius: 30px">
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>