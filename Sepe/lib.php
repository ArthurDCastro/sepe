<?php

/**
 * @param array $equacao
 * @return string
 */
function passoApasso(array $equacao){
    $operacoes           = ["+", "-", "/", "*", ",1/2)", ",2)", "pow("];
    $operacoesMascaradas = ["+", "-", "/", "*", ",1/2)", ",2)", "pow("];

    $passoApasso = '';
    foreach ($equacao as $eq){
        $verificaOp = true;
        foreach ($operacoes as $key => $op){
            if ($eq == $op){
                $passoApasso = $passoApasso .' '. $operacoesMascaradas[$key];
                $verificaOp = false;
                break;
            }
        }
        if ($verificaOp){
            $passoApasso = $passoApasso .' '. $eq;
        }

    }
    return $passoApasso;
}

/**
 * @param array $dados
 * @param array $incognitas
 * @param $formula
 * @return array
 */
function resolveEquacao(array $dados, array $incognitas, $formula){

    $resultado = "ainda nao deu"; //Define valor para resultado

    $operacoes         = ["+", "-", "/", "*", ",1/2)", ",2)"];
    $operacoesInversas = ["-", "+", "*", "/", ",2)", ",1/2)"];

    $equacaoAberta = explode(" ", $formula);

    $equacao = [];
    $passoApasso = [];
    $passoApasso[] = $formula;
    $verificaPow = false;

    foreach ($dados as $keyDado => $dado){
        if($keyDado == $dado){
            $incognitaBuscada = $dado;
        }
    }

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
            if (isset($dados[$termo])){
                $equacao[] = $dados[$termo];
            }
        } else {
            $equacao[] = $termo;
        }
    } //Substitui incognitas

    $passoApasso[] = passoApasso($equacao);

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
        $passoApasso[] = $incognitaBuscada . ' = ' . $resultado;

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
                $passoApasso[] = passoApasso($equacao);
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
        $passoApasso[] = passoApasso($equacao);

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
        $passoApasso[] = passoApasso($equacao);
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
            $passoApasso[] = passoApasso($equacao);

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
                    $passoApasso[] = $incognitaBuscada . ' = ' . $lado1semIncognita . ' / ' . $equacao[0];
                    eval('$resultado =' . $lado1semIncognita . '/' . $equacao[0] . ';');
                    $passoApasso[] = $incognitaBuscada . ' = ' . $resultado;
                } else {
                    $lado1semIncognita = '';
                    $count = count($equacao);
                    for ($i = $posicaoDivisao + 1; $i < $count; $i++){
                        $lado1semIncognita = $lado1semIncognita . $equacao[$i];
                    }
                    eval('$ladosemIncognita =' . $lado1semIncognita . ';');
                    $passoApasso[] = $incognitaBuscada . ' = ' . $lado1semIncognita . ' * ' . $equacao[0];
                    eval('$resultado =' . $equacao[0] . '*' . $lado1semIncognita . ';');
                    $passoApasso[] = $incognitaBuscada . ' = ' . $resultado;
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
                        $passoApasso[] = passoApasso($equacao);
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
                    $passoApasso[] = $incognitaBuscada . ' = ' . 'pow( ' . $equacao[0] . ' / ' . $isoladoDaIncognita . ' ,1/2)';
                    eval('$resultado =' . 'pow(' . $equacao[0] . '/' . $isoladoDaIncognita . ',1/2);');
                    $passoApasso[] = $incognitaBuscada . ' = ' . $resultado;
                } else {
                    eval('$isoladoDaIncognita =' . $isoladoDaIncognita . ';');
                    $passoApasso[] = $incognitaBuscada . ' = ' . $equacao[0] . '/' . $isoladoDaIncognita;
                    eval('$resultado =' . $equacao[0] . '/' . $isoladoDaIncognita . ';');
                    $passoApasso[] = $incognitaBuscada . ' = ' . $resultado;
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
                    $passoApasso[] = passoApasso($equacao);
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
                $passoApasso[] = $incognitaBuscada . ' = ' . 'pow( ' . $equacao[0] . ' -1 * ( ' . $lado1semIncognita . ' ) ,1/2)';
                eval('$resultado =' . 'pow(' . $equacao[0] . '-1*(' . $lado1semIncognita . ') ,1/2);');
                $passoApasso[] = $incognitaBuscada . ' = ' . $resultado;
            } else {
                eval('$isoladoDaIncognita =' . $lado1semIncognita . ';');
                $passoApasso[] = $incognitaBuscada . ' = ' . $equacao[0] . '-1 * ( ' . $lado1semIncognita . ' )';
                eval('$resultado =' . $equacao[0] . '-1*(' . $lado1semIncognita . ') ;');
                $passoApasso[] = $incognita . ' = ' . $resultado;
            }
        }
    }

    $final = [
        'resultado' => $resultado,
        'passoApasso' => $passoApasso
    ];

    return $final;
}

/**
 * @param array $formulasComIncognitas
 * @param array $dados
 * @param $apenasCompletas
 * @return array
 */
function pontua(array $formulasComIncognitas, array $dados, $apenasCompletas){
    $formulasPossiveis = [];
    $verificaFormula = false;
    foreach ($formulasComIncognitas as $key => $formulaCI){
        $pontos = 0;
        $formulaCI = (array) $formulaCI;
        foreach ($formulaCI["incog"] as $incog){
            foreach ($dados as $keyDado => $value){
                if ($keyDado == $incog){
                    $pontos++;
                }
            }
            if ($pontos >= count($formulaCI["incog"])){
                $formulasPossiveis[] = $formulaCI;
                $verificaFormula = true;
            } elseif ($pontos >= count($formulaCI["incog"]) -1 and !$apenasCompletas){
                $formulasPossiveis[] = $formulaCI;
            }
        }

    } //Puxa formulas apartir de pontuação

    return [
        "formulasPossiveis" => $formulasPossiveis,
        "verificaFormula" => $verificaFormula
    ];
}

/**
 * @param $caminho
 * @param array $dados
 * @return array
 */
function buscaEquacao($caminho, array $dados){
    $formulas = json_decode(file_get_contents($caminho, true));

    $incognitas= [];
    foreach ($dados as $key => $dado){
        if ($key == $dado and gettype($dado) == "string"){
            $incognitas[] = $key;
        }
    } //Encontra Incognitas

    $formulasComIncognitas = [];
    foreach ($formulas as $key => $formula){
        $formula = (array) $formula;
        foreach ($formula["incog"] as $incog){
            foreach ($incognitas as $incognita){
                if ($incognita == $incog){
                    $formulasComIncognitas[] = $formula;
                    break;
                }
            }
        }

    } //Puxa apenas os arrays com incognitas

    $pontua = pontua($formulasComIncognitas, $dados, false);

    $formulasPossiveis = $pontua["formulasPossiveis"];
    $verificaFormula   = $pontua["verificaFormula"];

    $resultadoFinal = [];
    if ($verificaFormula and count($incognitas) == 1){
        $count = count($formulasPossiveis);

        $resultadoFinal[] = [
            "formula" => $formulasPossiveis[rand(0, $count -1)],
            "dados"   => $dados
        ];
    } else {
        foreach ($formulasPossiveis as $formulaPossivel){
            foreach ($formulaPossivel["incog"] as $incog){
                $verificaIncog = true;
                foreach ($dados as $keyDado => $value){
                    if ($keyDado == $incog){
                        $verificaIncog = false;
                    }
                }
                if ($verificaIncog){
                    $dados[$incog] = $incog;
                }
            }
        }

        $pontua = pontua($formulas, $dados, true);

        $formulasPossiveis = $pontua["formulasPossiveis"];

        foreach ($formulasPossiveis as $key => $formulaPossivel){
            $novoDado = [];
            foreach ($formulaPossivel["incog"] as $incog){
                foreach ($dados as $keyDado => $dado){
                    if ($keyDado == $incog){
                        $novoDado[$keyDado] = $dado;
                    }
                }
            }
            $resultadoFinal[] = [
                "formula" => $formulaPossivel,
                "dados"   => $novoDado
            ];
        }
    }



    return $resultadoFinal;
}

$dados = [
    "v"  => 10,
    "v0" => 0,
    "a"  => 2,
    "t" => "t"
];


$formulas =  buscaEquacao("formulas.json", $dados);

$count = count($formulas);
for ($i = 0; $i < $count; $i++){
    $resultado = resolveEquacao($formulas[$i]["dados"], $formulas[$i]["formula"]["incog"], $formulas[$i]["formula"]["formula"]);
    foreach ($resultado['passoApasso'] as $passoApasso){
        echo $passoApasso. "\n";
    }
    if (isset($formulas[$i+1])){
        foreach ($formulas[$i]["dados"] as $dados1){
            foreach ($formulas[$i+1]["dados"] as $dados2){
                if ($dados1 == $dados2){
                    $formulas[$i+1]["dados"][$dados2] = $resultado['resultado'];
                }
            }
        }
    }
    echo "\n";
}

