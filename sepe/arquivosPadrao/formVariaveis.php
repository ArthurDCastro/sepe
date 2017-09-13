<?php

    $variaveis = buscaVariaveis("json/formulas.json");
?>

<form class="form-horizontal" action="fisica.php?acao=dados" method="post">
    <fieldset>
        <!-- Form Name -->
        <legend style="color: white">ShowCrazy</legend>
        <!-- Multiple Checkboxes -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="checkboxes">Escolha as variaveis:</label>
            <div class="col-md-8">
                <?php foreach($variaveis as $variavel):?>
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label for="<?php echo $variavel ?>">
                                <input type="checkbox" name="variaveis[]" id="<? echo $variavel ?>" value="<?php echo $variavel ?>">
                                <?php echo $variavel ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="singlebutton">Enviar Variaveis</label>
            <div class="col-md-4">
                <button id="singlebutton" name="singlebutton" class="btn btn-primary">Enviar</button>
            </div>
        </div>

    </fieldset>
</form>