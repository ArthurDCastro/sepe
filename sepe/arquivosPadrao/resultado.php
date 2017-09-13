<?php

    $dados = [];
    foreach ($_POST as $keyPost => $post){
        if (empty($post) and $post != "0"){
            $dados[$keyPost] = $keyPost;
        } elseif ($post == "0"){
            $dados[$keyPost] = "0";
        } else {
            $dados[$keyPost] = $post;
        }
    }

    $formulas =  buscaEquacao("json/formulas.json", $dados);

    $count = count($formulas);
    for ($i = 0; $i < $count; $i++){
        $resultado = resolveEquacao($formulas[$i]["dados"], $formulas[$i]["formula"]["incog"], $formulas[$i]["formula"]["formula"]);
        foreach ($resultado['passoApasso'] as $passoApasso){
            echo $passoApasso. "<br>";
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
        echo "<br>";
    }
?>