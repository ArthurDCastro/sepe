<?php
    $todas = buscaVariaveis("json/", "fisica.json");
    $variaveis = arrumaVariaveis($todas, $_POST["variaveis"])
?>

<form class="form-horizontal" action="fisica.php?acao=resultado" method="post">
    <fieldset>

        <!-- Form Name -->
        <legend>Form Name</legend>

        <!-- Prepended text-->
        <?php foreach($variaveis as $variavel):?>
        <div class="form-group">
            <label class="col-md-4 control-label" for="prependedtext"><?php echo $variavel["nome"]; ?></label>

            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><?php echo $variavel["mascara"]; ?></span>
                    <input id="<?php echo $variavel["nome"]; ?>" name="<?php echo $variavel["nome"]; ?>" class="form-control" placeholder="<?php echo $variavel["mascara"]; ?>" type="number">
                </div>
            </div>

        </div>
        <?php endforeach; ?>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="singlebutton">Enviar Dados</label>
            <div class="col-md-8">
                <button id="singlebutton" class="btn btn-primary">Enviar</button>
                <a href="fisica.php?acao=variaveis" id="singlebutton" class="btn btn-primary">Voltar</a>
            </div>
        </div>

    </fieldset>
</form>