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

    $formulas =  buscaEquacao("json/fisica.json", $dados);

    $count = count($formulas);

    ?>
<style>
    ul {
        margin: 10px;
    }
    h4 {
        margin-top: 20px;
    }
</style>
<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
    <?php
        for ($i = 0; $i < $count; $i++):
        $resultado = resolveEquacao($formulas[$i]["dados"], $formulas[$i]["formula"]["incog"], $formulas[$i]["formula"]["formula"]);
        $incognita = "";
        foreach ($formulas[$i]["dados"] as $key => $dado){
            if ($dado == $key){
                $incognita = $dado;
            }
        }
    ?>
            <h4>Para descobrirmos "<?php echo $incognita ?>" usamos a formula d<?php echo $formulas[$i]["formula"]["nomeFormula"] ?></h4>
        <ul>
            <?php foreach ($resultado['passoApasso'] as $passoApasso): ?>
                <li style="list-style-type: none"><?php echo $passoApasso ?></li>
            <?php endforeach;
            if (isset($formulas[$i+1])){
                foreach ($formulas[$i]["dados"] as $dados1){
                    foreach ($formulas[$i+1]["dados"] as $dados2){
                        if ($dados1 == $dados2){
                            $formulas[$i+1]["dados"][$dados2] = $resultado['resultado'];
                        }
                    }
                }
            }?>
        </ul>
    <?php endfor; ?>
    <!-- Button -->
    <div class="form-group">
        <label class="col-md-4 control-label" for="singlebutton">Voltar para o inicio</label>
        <div class="col-md-8">
            <a id="singlebutton" class="btn btn-primary" href="index.php">Voltar</a>
        </div>
    </div>
    </div>
</div>