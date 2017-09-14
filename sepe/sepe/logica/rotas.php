<?php

function variaveis(){
    print_r($_POST["variaveis"]);
}

function dados(){}

function resposta(){}

switch ($_GET["acao"]){
    case "variaveis":
    case "":
        variaveis();
        break;

    case "dados":
        dados();
        break;

    case "resposta":
        resposta();
        break;
}