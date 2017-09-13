<?php
include "lib.php";

$dados = [
    "v0" => 10,
    "t"  => 8,
    "a"  =>  2,
    "s0" => '0',
    "s"  => "s"
];

print_r(buscaVariaveis("../json/","fisica.json"));

/*$formulas =  buscaEquacao("fisica.json", $dados);

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
}*/