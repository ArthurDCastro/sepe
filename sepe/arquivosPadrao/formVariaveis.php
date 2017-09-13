<?php

    $variaveis = buscaVariaveis("json/", "fisica.json");
?>

<form class="form-horizontal" action="fisica.php?acao=dados" method="post">
    <fieldset>
        <!-- Form Name -->
        <legend style="color: white">ShowCrazy</legend>
        <!-- Multiple Checkboxes -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="checkboxes">Escolha as variaveis:</label>
            <div class="col-md-8">
                <?php foreach($variaveis as $key => $variavel):?>
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label for="<?php echo $key ?>">
                                <input type="checkbox" name="variaveis[]" id="<? echo $key ?>" value="<?php echo $key?>">
                                <?php echo $variavel["mascara"] ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="singlebutton">Enviar Dados</label>
            <div class="col-md-8">
                <button id="singlebutton" class="btn btn-primary">Enviar</button>
            </div>
        </div>

    </fieldset>
</form>