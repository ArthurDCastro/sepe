<?php

/**
 * @param array $dados
 * @param array $incognitas
 * @param $formula
 * @return string
 */
function resolveEquacao(array $dados, array $incognitas, $formula){

    $resultado = "ainda nao deu"; //Define valor para resultado

    $operacoes         = ["+", "-", "/", "*", ",1/2)", ",2)"];
    $operacoesInversas = ["-", "+", "*", "/", ",2)", ",1/2)"];

    $equacaoAberta = explode(" ", $formula);

    $equacao = [];
    $verificaPow = false;

    foreach ($equacaoAberta as $termo){
        $verificaOperacao = true;
        foreach ($operacoes as $operacao){
            if ($termo == $operacao or $termo == "="){
                $verificaOperacao = false;
            }
        }

        $verificaNumero = false;
        foreach ($incognitas as $incognita){
            if ($incognita == $termo){
                $verificaNumero = true;
            }
        }
        if ($verificaOperacao and $verificaNumero){
            $equacao[] = $dados[$termo];
        } else {
            $equacao[] = $termo;
        }
    } //Substitui incognitas

    $igual = false;
    $lado[0] = '';
    $lado[1] = '';
    $posicaoIgual = 0;
    $posicaoIncognita = 0;
    foreach ($equacao as $key => $termo){
        if ($termo == '='){
            $igual = true;
            $posicaoIgual = $key;
        } elseif ($igual){
            $lado[1] =  $lado[1] . $termo;
            foreach ($incognitas as $incognita){
                if ($termo == $incognita){
                    $posicaoIncognita = $key;
                }
            }
        } else {
            $lado[0] = $lado[0] . $termo;
            foreach ($incognitas as $incognita){
                if ($termo == $incognita){
                    $posicaoIncognita = $key;
                }
            }
        }
    } //Separa os dois lados da equacao, verica a posição do igual e da incognita

    $primeiroPow = true;
    foreach ($equacao as $key => $eq){
        if ($eq == 'pow(' and $primeiroPow){
            $posicaoPow['abre'] = $key;
            $primeiroPow = false;
        } elseif ($eq == ',1/2)'){
            $posicaoPow['fecha'] = $key;
        }
    }
    if  (!isset($posicaoPow['fecha']) or !isset($posicaoPow['abre'])){
        $posicaoPow['fecha'] = 0;
        $posicaoPow['abre'] = 0;
    }

    if ($posicaoIncognita < $posicaoIgual){

        eval('$resultado =' . $lado[1] . ';');  //Se a incognita ja estiver isolada

    } /*Verica a posicao da incognita, se ela estiver isolada resolva a equação*/ else {

        eval('$lado[0] =' . $lado[0] . ';');

        if (isset($posicaoPow)){
            if ($posicaoPow['abre'] == 2 and $posicaoPow['fecha'] == count($equacao) -1){
                $novaEquacao = [];
                foreach ($equacao as $key => $eq){
                    if ($key == 0){
                        eval('$lado[0] =' . 'pow(' .$lado[0] . ',2);');
                        $novaEquacao[] = $lado[0];
                    } elseif ($posicaoPow['abre'] != $key and $posicaoPow['fecha'] != $key){
                        $novaEquacao[] = $eq;
                    }
                }
                $equacao = $novaEquacao;
                foreach ($equacao as $key => $eq){
                    foreach ($incognitas as $incognita){
                        if ($eq == $incognita){
                            $posicaoIncognita = $key;
                        } elseif ( '=' == $eq){
                            $posicaoIgual = $key;
                        }
                    }

                }
            }
        } //Pow

        $verificaAlocarLado0 = true;
        $equacaoNova = [];
        foreach ($equacao as $key => $eq){
            if ($key < $posicaoIgual and $verificaAlocarLado0){
                $equacaoNova[] = $lado[0];
                $verificaAlocarLado0 = false;
            } elseif ($key >= $posicaoIgual){
                $equacaoNova[] = $eq;
            }
        } //Realoca as posiçoes da equação
        $equacao = $equacaoNova;


        $i = 0;
        $verificaParenteses = false;
        $termo = [];
        $verificaAlocarTermo = false;
        $termoResolvido = '';
        $termoIncognita = -1;
        $novaEquacao = [];
        foreach ($equacao as $key => $eq){
            if (!isset($termo[$i])){
                $termo[$i] = '';
            }

            if ($eq == '(' and $key > $posicaoIgual){
                $verificaParenteses = true;
                $verificaAlocarTermo = true;
            } elseif ($eq == ')' and $key > $posicaoIgual){
                $verificaParenteses = false;
                $i++;
            } elseif ($verificaParenteses){
                $termo[$i] = $termo[$i] . $eq . ' ';
                if ($key == $posicaoIncognita){
                    $termoIncognita = $i;
                }
            } else {
                if ($verificaAlocarTermo){
                    if ($termoIncognita != $i - 1){
                        eval('$termoResolvido =' . $termo[$i - 1 ]. ';');
                    } else {
                        $termoResolvido = $termo[$i - 1];
                    }

                    $novaEquacao[] = $termoResolvido;
                    $verificaAlocarTermo = false;

                    foreach ($operacoes as $op){
                        if ($op == $eq){
                            $novaEquacao[] = $eq;
                        }
                    }
                } else {
                    $novaEquacao[] = $eq;
                }
            }
        } //Separa e resolve os termos
        if ($verificaAlocarTermo){
            if ($termoIncognita != $i -1){
                eval('$termoResolvido =' . $termo[$i-1] . ';');

            } else {
                $termoResolvido = $termo[$i-1];
            }

            $novaEquacao[] = $termoResolvido;
        } //Faz parte ^
        $equacao = $novaEquacao;
        //ok

        if (isset($termo[$termoIncognita])){
            foreach ($equacao as $key => $eq){
                if ( '=' == $eq){
                    $posicaoIgual = $key;
                }
            }

            $lado1semIncognita = '';
            foreach ($equacao as $key => $eq){
                if ($eq != $termo[$termoIncognita] and $posicaoIgual < $key){
                    $lado1semIncognita = $lado1semIncognita . $eq;
                } elseif ($eq == $termo[$termoIncognita]){
                    $lado1semIncognita = $lado1semIncognita . 0;
                }
            }
            $equacaoNova = [];
            eval('$equacaoNova[0] =' . $lado[0] . '-1*(' . $lado1semIncognita . ');');
            $equacaoNova[1] = '=';
            foreach (explode(" ", $termo[$termoIncognita]) as $eq){
                $equacaoNova[] = $eq;
            }
            $equacao = $equacaoNova;

            $verificaDivisao = false;
            foreach ($equacao as $key => $eq){
                if ($eq == '/'){
                    $verificaDivisao = true;
                    $posicaoDivisao = $key;
                }
            }

            if ($verificaDivisao){

                foreach ($equacao as $key => $eq){
                    foreach ($incognitas as $incog){
                        if ($incog == $eq){
                            $posicaoIncognita = $key;
                        }
                    }
                }

                if ($posicaoIncognita > $posicaoDivisao){
                    $lado1semIncognita = '';
                    for ($i = 2; $i < $posicaoDivisao; $i++){
                        $lado1semIncognita = $lado1semIncognita . $equacao[$i];
                    }
                    eval('$ladosemIncognita =' . $lado1semIncognita . ';');
                    eval('$resultado =' . $lado1semIncognita . '/' . $equacao[0] . ';');
                } else {
                    $lado1semIncognita = '';
                    $count = count($equacao);
                    for ($i = $posicaoDivisao + 1; $i < $count; $i++){
                        $lado1semIncognita = $lado1semIncognita . $equacao[$i];
                    }
                    eval('$ladosemIncognita =' . $lado1semIncognita . ';');
                    eval('$resultado =' . $equacao[0] . '*' . $lado1semIncognita . ';');
                }

            } else {
                foreach ($equacao as $key => $eq){
                    foreach ($incognitas as $incognita){
                        if ($eq == $incognita){
                            $posicaoIncognita = $key;
                        } elseif ( '=' == $eq){
                            $posicaoIgual = $key;
                        }
                    }

                }

                $primeiroPow = true;
                unset($posicaoPow);
                foreach ($equacao as $key => $eq){
                    if ($eq == 'pow(' and $primeiroPow){
                        $posicaoPow['abre'] = $key;
                        $primeiroPow = false;
                    } elseif ($eq == ',2)'){
                        $posicaoPow['fecha'] = $key;
                    }
                }

                if (isset($posicaoPow)) {
                    if ($posicaoIncognita > $posicaoPow["abre"] and $posicaoIncognita < $posicaoPow['fecha']) {
                        $novaEquacao = [];
                        $constroiPow = '';
                        $posicaoIncognita = -1;
                        foreach ($equacao as $key => $eq){
                            if ($key >= $posicaoPow["abre"] and $key <= $posicaoPow['fecha']){
                                $constroiPow = $constroiPow . ' ' . $eq;
                            } else {
                                $novaEquacao[] = $eq;
                                ++$posicaoIncognita;
                            }
                            if ($key == $posicaoPow['fecha'] ){
                                $novaEquacao[] = $constroiPow;
                            }
                        }
                        $equacao = $novaEquacao;
                        $verificaPow = true;
                    }
                }


                $isoladoDaIncognita = '';
                foreach ($equacao as $key => $eq){
                    if ($key > $posicaoIgual and $key != $posicaoIncognita){
                        $isoladoDaIncognita = $isoladoDaIncognita . $eq;

                    } elseif ($key == $posicaoIncognita){
                        $isoladoDaIncognita = $isoladoDaIncognita . 1;
                    }
                }

                if ($verificaPow){
                    eval('$isoladoDaIncognita =' . $isoladoDaIncognita . ';');
                    eval('$resultado =' . 'pow(' . $equacao[0] . '/' . $isoladoDaIncognita . ',1/2);');
                } else {
                    eval('$isoladoDaIncognita =' . $isoladoDaIncognita . ';');
                    eval('$resultado =' . $equacao[0] . '/' . $isoladoDaIncognita . ';');
                }

            }

        } else {
            $primeiroPow = true;
            $posicaoPow = [];
            foreach ($equacao as $key => $eq){
                if ($eq == 'pow(' and $primeiroPow){
                    $posicaoPow['abre'] = $key;
                    $primeiroPow = false;
                } elseif ($eq == ',2)'){
                    $posicaoPow['fecha'] = $key;
                }
            }

            if (isset($posicaoPow)) {
                if ($posicaoIncognita > $posicaoPow["abre"] and $posicaoIncognita < $posicaoPow['fecha']) {
                    $novaEquacao = [];
                    $constroiPow = '';
                    $posicaoIncognita = -1;
                    foreach ($equacao as $key => $eq){
                        if ($key >= $posicaoPow["abre"] and $key <= $posicaoPow['fecha']){
                            $constroiPow = $constroiPow . ' ' . $eq;
                        } else {
                            $novaEquacao[] = $eq;
                            ++$posicaoIncognita;
                        }
                        if ($key == $posicaoPow['fecha'] ){
                            $novaEquacao[] = $constroiPow;
                        }
                    }
                    $equacao = $novaEquacao;
                    $verificaPow = true;
                }
            }


            foreach ($equacao as $key => $eq){
                foreach ($incognitas as $incognita){
                    if ($eq == $incognita){
                        $posicaoIncognita = $key;
                    } elseif ( '=' == $eq){
                        $posicaoIgual = $key;
                    }
                }
            }

            foreach ($equacao as $key => $eq){
                if ($verificaPow){
                    if ($constroiPow == $eq){
                        $posicaoIncognita = $key;
                    }
                }
            }

            $lado1semIncognita = '';
            foreach ($equacao as $key => $eq){
                if ($key != $posicaoIncognita and $posicaoIgual < $key){
                    $lado1semIncognita = $lado1semIncognita . $eq; //BEAT
                }
            }

            if ($verificaPow){
                eval('$isoladoDaIncognita =' . $lado1semIncognita . ';');
                eval('$resultado =' . 'pow(' . $equacao[0] . '-1*(' . $lado1semIncognita . ') ,1/2);');
            } else {
                eval('$isoladoDaIncognita =' . $lado1semIncognita . ';');
                eval('$resultado =' . $equacao[0] . '-1*(' . $lado1semIncognita . ') ;');
            }
        }
    }

    return $resultado;
}

/**
 * @param $caminho
 * @param array $dados
 * @return array|string
 */
function buscaEquacoes($caminho, array $dados){

    $formulas = json_decode(file_get_contents($caminho, true));

    $formulasPossiveis = [];
    foreach ($formulas as $key => $formula){
        $formulasPossiveis[] = [
            "incog"  => $key,
            "pontos" => 0
        ];
        $pontos = 0;
        foreach ($formula as $keyForm => $form) {
            if ($keyForm == "incog"){
                foreach ($form as $incog){
                    if (isset($dados[$incog])) {
                        $pontos++;
                    }
                }
                if ($pontos >= count($form)){
                    $formulasPossiveis[$key]["pontos"] = $pontos;
                }
            }
        }
    }

    $formulaPossivel = [];
    foreach ($formulasPossiveis as $formula){
        if ($formula["pontos"] > 0){
            $formulaPossivel[] = $formula;
        }
    }

    $formulaFinal = "";
    if (count($formulaPossivel) > 1){

    } else {
        $formulaFinal = (array) $formulas[$formulaPossivel[0]["incog"]];
    }

    return $formulaFinal;

}

$dados = [
    "v"  => 10,
    "dd" => 5,
    "dt"  => "dt"
];


$formulas =  buscaEquacoes("formulas.json", $dados);


echo "\n ---------- \n ". resolveEquacao($dados, $formulas["incog"], $formulas["formula"]) . "\n ----------";

