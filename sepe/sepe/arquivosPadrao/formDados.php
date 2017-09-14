<?php
    $todas = buscaVariaveis("json/", "fisica.json");
    $variaveis = arrumaVariaveis($todas, $_POST["variaveis"])
?>

<form class="form-horizontal" action="conta.php?acao=resultado" method="post">
    <fieldset>

        <!-- Form Name -->
        <legend>Form Name</legend>

        <!-- Prepended text-->
        <?php foreach($variaveis as $key => $variavel):?>
        <div class="form-group">
            <label class="col-md-4 control-label" for="prependedtext"><?php echo $variavel["nome"]; ?></label>

            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-addon"><?php echo $variavel["mascara"]; ?></span>
                    <input id="<?php echo $variavel["nome"]; ?>" name="<?php echo $key ?>" class="form-control" placeholder="<?php echo $variavel["mascara"]; ?>" type="number">
                </div>
            </div>

        </div>
        <?php endforeach; ?>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="singlebutton">Enviar Dados</label>
            <div class="col-md-8">
                <button id="singlebutton" class="btn btn-primary">Enviar</button>
                <a href="conta.php?acao=variaveis" id="singlebutton" class="btn btn-primary">Voltar</a>
            </div>
        </div>

    </fieldset>
</form>